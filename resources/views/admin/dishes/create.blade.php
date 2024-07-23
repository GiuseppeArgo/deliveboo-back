@extends('layouts.admin')

@section('content')

    {{-- container --}}
    <div class="form-container p-5">

        <div class="d-flex flex-column justify-content-center align-items-center gap-2 mb-2">
            {{-- title --}}
            <h1 class="text-center">Aggiungi un piatto</h1>
            {{-- /title --}}

            {{-- button --}}
            <div>
                <a href="{{ route('admin.dishes.index') }}" class="btn btn-primary">
                    <i class="fa-solid fa-circle-arrow-left"></i>
                    Torna al menu
                </a>
                <a class="btn btn-primary" href="{{ route('admin.restaurants.index') }}">
                    <i class="fa-solid fa-circle-arrow-left"></i>
                    Torna alla home
                </a>
            </div>
            {{-- /button --}}

        </div>

        <form action="{{ route('admin.dishes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Name --}}    {{-- C'è un controllo di piu --}}
            <div class="mb-3">
                <label for="name" class="form-label">Nome Piatto <span class="asterisco">*</span>
                    {{-- error message --}}
                    @error('name')
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @enderror
                    {{-- errore unique name --}}
                    @if (session('error'))
                    <span class="text-danger">{{ session('error') }}</span>
                    @endif
                    {{-- /error message --}}


                </label>
                <input value="{{ old('name') }}" type="text" minlength="5" maxlength="20" name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    placeholder="es. Lasagna" required id="name" aria-describedby="name">
            </div>
            {{-- /name --}}

            {{-- Description --}}
            <div class="mb-3">
                <label for="description" class="form-label">Descrizione <span class="asterisco">*</span>

                    {{-- error message --}}
                    @error('description')
                        <span class="text-danger">{{ $errors->first('description') }}</span>
                    @enderror
                    {{-- /error message --}}

                </label>

                <textarea class="form-control @error('description') is-invalid @enderror" name="description" minlength="5" maxlength="200" id="description" rows="3" placeholder="es. breve descrizione e ingredienti..." required>{{ old('description') }}</textarea>
            </div>
            {{-- /Description --}}

            {{-- Price --}}
            <div class="mb-3">
                <label for="price" class="form-label">Prezzo <span class="asterisco">*</span>

                    {{-- error message --}}
                    @error('price')
                        <span class="text-danger">{{ $errors->first('price') }}</span>
                    @enderror
                    {{-- /error message --}}

                </label>

                <input value="{{ old('price') }}" type="number" name="price"
                       class="form-control @error('price') is-invalid @enderror"
                       placeholder="es. 10.00" id="price" aria-describedby="price" required
                       min="3" max="30" step="0.01">
            </div>
            {{-- /Price --}}

            {{-- input file image --}}
            <div class="mb-3">
                <label for="image" class="form-label">Immagine <span class="asterisco">*</span>

                    {{-- error message --}}
                    @error('image')
                        <span class="text-danger">{{ $errors->first('image') }}</span>
                    @enderror
                    {{-- /error message --}}

                </label>

                <span id="errorImage" class="text-danger"></span>
                <input type="file" name="image" id="image" aria-describedby="image"
                    class="form-control @error('image') is-invalid @enderror" required>
            </div>
            {{-- /input file image --}}

            <div class="container-preview m-auto mt-3">
                {{-- img preview --}}
                <div class="mt-2 card-img">
                    <img id="imagePreview" class="hide mb-3" src="" alt="new-image">
                </div>
                {{-- /img preview --}}

                {{-- button add and remove --}}
                <div class="container">
                    <div class="row gap-2 mt-3 align-items-center justify-content-center">
                        <button class="btn btn-success col" type="submit">Crea piatto</button>
                    </div>
                </div>
                {{-- /button add and remove --}}
            </div>

            {{-- hide input --}}
            <input type="text" name="restaurant_id" class="hide" value="{{ $restaurant_id }}" required>
            {{-- hide input --}}

        </form>
        <div class="mt-5">
            <span class="asterisco">*</span> ⁠questi campi sono obbligatori
        </div>
    </div>
    {{-- /container --}}

    {{-- javascript validation image --}}
    <script>
        function validateImage(file) {
            return new Promise((resolve) => {
                // control extension image
                const allowedExtensions = ['jpg', 'jpeg', 'png'];
                const extension = file.name.split('.').pop().toLowerCase();
                if (!allowedExtensions.includes(extension)) {
                    resolve({ valid: false, error: 'Tipo di file non valido.' });
                    return;
                }

                // control size image
                const maxSize = 1024 * 1024 * 2; // max 2 mm
                if (file.size > maxSize) {
                    resolve({ valid: false, error: 'Il file è troppo grande. Dimensione massima consentita: 2 MB.' });
                    return;
                }

                resolve({ valid: true });
            });
        }
        //listen change tu imput file
        document.querySelector('#image').addEventListener('change', async function() {
            const file = this.files[0];
            const imgElem = document.getElementById("imagePreview");
            const errImg = document.getElementById("errorImage");

            if (file) {
                // wait return of function
                const { valid, error } = await validateImage(file);
                //if big image o invalid format reset input and hide image
                if (!valid) {
                    imgElem.src = "";
                    imgElem.classList.add('hide');
                    errImg.textContent = error;
                    this.value = '';
                } else {
                    // show img preview
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        imgElem.src = event.target.result;
                        imgElem.classList.remove('hide');
                    };
                    reader.readAsDataURL(file);

                    // reset value error
                    errImg.textContent = "";
                }
            }
        });

        // hide image to start
        document.addEventListener('DOMContentLoaded', function() {
            const imagePreview = document.getElementById("imagePreview");
            if (!imagePreview.src) {
                imagePreview.classList.add('hide');
            }
        });
    </script>
    {{-- /javascript validation image --}}

@endsection
