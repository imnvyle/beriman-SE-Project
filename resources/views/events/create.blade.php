<x-app-layout>
    <div class="min-h-screen py-10 px-4 pb-24"> 
        <div class="max-w-2xl mx-auto bg-white p-8 rounded-3xl shadow-sm border">
            <h2 class="text-2xl font-black text-gray-800 mb-6 italic">Post New Event</h2>

            <form id="eventForm" action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                @csrf 

                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Event Title</label>
                    <input type="text" name="title" class="w-full border-gray-200 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Enter a catchy name" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Upload Poster</label>
                    <div class="border-2 border-dashed border-gray-200 rounded-2xl p-6 text-center bg-gray-50">
                        <input type="file" name="poster" class="w-full text-sm text-gray-500" accept="image/*" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Event Date</label>
                        <input type="date" name="event_date" class="w-full border-gray-200 rounded-2xl px-4 py-3 outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Venue</label>
                        <input type="text" name="venue" class="w-full border-gray-200 rounded-2xl px-4 py-3 outline-none" placeholder="Location" required>
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="5" class="w-full border-gray-200 rounded-2xl px-4 py-3 outline-none" placeholder="Tell everyone about the event..." required></textarea>
                </div>

                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" id="cancelBtn" class="px-6 py-3 rounded-xl bg-red-100 border border-black-500 text-red-600 font-semibold hover:bg-red-50 transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-8 py-3 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-400 shadow-md transition">
                        Publish Event
                    </button>
                </div>
            </form>

            <script>
                let formChanged = false;
                const form = document.getElementById('eventForm');

                form.querySelectorAll('input, textarea, select').forEach((element) => {
                    element.addEventListener('input', () => {
                        formChanged = true;
                    });
                });

                form.addEventListener('submit', () => {
                    formChanged = false;
                });

                window.addEventListener('beforeunload', function (e) {
                    if (formChanged) {
                        e.preventDefault();
                        e.returnValue = '';
                    }
                });

                document.getElementById('cancelBtn').addEventListener('click', function () {
                    if (!formChanged || confirm('You have unsaved changes. Are you sure you want to discard them?')) {
                        window.location.href = "{{ route('events.all') }}";
                    }
                });

                document.querySelectorAll('a[href]').forEach(link => {
                    link.addEventListener('click', function (e) {
                        if (formChanged) {
                            const leave = confirm(
                                'You have unsaved changes. Are you sure you want to discard them?'
                            );
                            if (!leave) {
                                e.preventDefault();
                            }
                        }
                    });
                });
            </script>


        </div>
    </div>
</x-app-layout>