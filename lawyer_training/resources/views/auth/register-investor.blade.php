@extends('layouts.headfoot')
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .form-control-light {
            background-color: #f9f9f9; /* لون خلفية فاتح */
            color: #333; /* لون النص */
            border: 1px solid #ccc; /* لون الحدود */
        }
        .form-control-light::placeholder {
            color: #999; /* لون النص الافتراضي */
        }
        .form-label-light {
            color: #333; /* لون النص للملصقات */
        }
        .btn-green {
            background-color: #38a169; /* لون الزر الأخضر */
            color: #fff; /* لون النص */
            border: 1px solid #38a169; /* لون الحدود */
        }
        .btn-green:hover {
            background-color: #2f855a; /* لون الزر الأخضر عند التمرير */
            border: 1px solid #2f855a; /* لون الحدود عند التمرير */
        }
    </style>
</head>
@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="min-h-screen flex flex-col items-center justify-center mt-28">
    <div class="w-full max-w-2xl px-6 py-4 bg-gray-800 text-white shadow-md overflow-hidden sm:rounded-lg">
        <form method="POST" action="{{ route('register.investor.store') }}" enctype="multipart/form-data"  style="margin-top: 120px !important;">
            @csrf
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="col-span-2">
                    <label for="entity_type" class="block font-medium text-sm form-label-light">{{ __('Register as Investor') }}</label>
                    <select name="entity_type" id="entity_type" class="block mt-1 w-full form-control-light">
                        <option value="individual">Individual</option>
                        <option value="company">Company</option>
                    </select>
                </div>

                <div>
                    <label for="name" class="block font-medium text-sm form-label-light">Name</label>
                    <input id="name" type="text" class="block mt-1 w-full form-control-light" name="name" required />
                </div>

                <div>
                    <label for="national_id" class="block font-medium text-sm form-label-light">National ID</label>
                    <input id="national_id" type="text" class="block mt-1 w-full form-control-light" name="national_id" required />
                </div>

                <div>
                    <label for="email" class="block font-medium text-sm form-label-light">Email</label>
                    <input id="email" type="email" class="block mt-1 w-full form-control-light" name="email" required />
                </div>

                <div>
                    <label for="phone" class="block font-medium text-sm form-label-light">Phone Number</label>
                    <input id="phone" type="text" class="block mt-1 w-full form-control-light" name="phone" required />
                </div>

                <div class="col-span-2">
                    <label for="address" class="block font-medium text-sm form-label-light">Address</label>
                    <input id="address" type="text" class="block mt-1 w-full form-control-light" name="address" required />
                </div>

                <div>
                    <label for="national_id_front" class="block font-medium text-sm form-label-light">National ID (Front)</label>
                    <input id="national_id_front" type="file" class="block mt-1 w-full form-control-light" name="national_id_front" required />
                </div>

                <div>
                    <label for="national_id_back" class="block font-medium text-sm form-label-light">National ID (Back)</label>
                    <input id="national_id_back" type="file" class="block mt-1 w-full form-control-light" name="national_id_back" required />
                </div>

                <div>
                    <label for="password" class="block font-medium text-sm form-label-light">Password</label>
                    <input id="password" type="password" class="block mt-1 w-full form-control-light" name="password" required />
                </div>

                <div>
                    <label for="password_confirmation" class="block font-medium text-sm form-label-light">Confirm Password</label>
                    <input id="password_confirmation" type="password" class="block mt-1 w-full form-control-light" name="password_confirmation" required />
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <button type="submit" class="ml-4 inline-flex items-center px-4 py-2 btn-green rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:bg-green-500 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2 transition ease-in-out duration-150">
                    Register
                </button>
            </div>
        </form>
    </div>
    @if (session('status'))
        <script>
            Swal.fire({
                title: 'Registration Successful',
                text: "{{ session('status') }}",
                icon: 'success',
                confirmButtonText: 'Go to Home'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('home') }}";
                }
            });
        </script>
    @endif
</div>
@endsection
