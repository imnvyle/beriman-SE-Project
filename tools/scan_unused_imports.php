<?php

$root = __DIR__ . '/../';
$excludeDirs = ['vendor', 'storage', 'node_modules'];
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));

$files = [];
foreach ($it as $file) {
    if (!$file->isFile()) continue;
    $path = $file->getRealPath();
    if (!preg_match('/\.php$/', $path)) continue;
    foreach ($excludeDirs as $ex) {
        if (strpos($path, DIRECTORY_SEPARATOR . $ex . DIRECTORY_SEPARATOR) !== false) continue 2;
    }
    $files[] = $path;
}

$unused = [];
foreach ($files as $file) {
    $contents = file_get_contents($file);
    // Find use statements (simple, line-based)
    preg_match_all('/^\s*use\s+([^;]+);/mi', $contents, $m, PREG_SET_ORDER);
    if (!$m) continue;
    $lines = preg_split("/\n/", $contents);
    foreach ($m as $match) {
        $full = trim($match[1]);
        // handle comma-separated uses? usually not in PHP files, ignore
        // handle aliases
        $parts = preg_split('/\s+as\s+/i', $full);
        $aliasPart = trim(end($parts));
        // In case aliasPart contains namespace segments, take last segment as the short name
        if (strpos($aliasPart, '\\') !== false) {
            $s = explode('\\', $aliasPart);
            $short = end($s);
        } else {
            $short = $aliasPart;
        }
        // strip leading backslashes/spaces
        $short = preg_replace('/^[\\\s]+/', '', $short);
        // Fallback: if short still contains backslashes, take last segment
        if (strpos($short, '\\') !== false) {
            $s = explode('\\', $short);
            $short = end($s);
        }
        // search for usage of short name in file excluding the use line
        // remove the use line occurrences from contents
        $contents_no_use = preg_replace('/^\s*use\s+' . preg_quote($match[1], '/') . '\s*;/mi', '', $contents);
        // word match
        if ($short === '') continue;
        if (!preg_match('/\b' . preg_quote($short, '/') . '\b/', $contents_no_use)) {
            // find line number of use statement
            $pos = strpos($contents, $match[0]);
            $line = substr_count(substr($contents, 0, $pos), "\n") + 1;
            $unused[] = [$file, $line, trim($match[0])];
        }
    }
}

if (empty($unused)) {
    echo "No unused use statements found.\n";
    exit(0);
}

foreach ($unused as $u) {
    list($file, $line, $text) = $u;
    echo "$file:$line: $text\n";
}

echo "\nTotal unused: " . count($unused) . "\n";
