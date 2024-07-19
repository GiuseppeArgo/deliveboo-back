@extends('layouts.admin')

@section('content')
    <h1 class="text-center mt-5">Crea Il tuo Ristorante</h1>
    <div class="form-container p-5">



        <form action="{{ route('admin.restaurants.store') }}" method="POST" enctype="multipart/form-data">

            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Nome Ristorante *
                    {{-- error message --}}
                    @error('name')
                        <span class="text-danger"> {{ $errors->first('name') }} </span>
                    @enderror
                    {{-- /error message --}}
                </label>
                <input value="{{ old('name') }}" type="text" minlength="3" maxlength="20" name="name"
                    class="form-control
                 {{-- dynamic class with red border --}}
                @error('name') is-invalid @enderror"
                    placeholder="es. Da Mario" {{-- /dynamic class with red border --}} id="name" aria-describedby="name_restaurant"
                    required>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Indirizzo *
                    {{-- error message --}}
                    @error('address')
                        <span class="text-danger"> {{ $errors->first('address') }} </span>
                    @enderror
                    {{-- /error message --}}
                </label>
                <input value="{{ old('address') }}" type="text" name="address" minlength="3" maxlength="20"
                    class="form-control @error('address') is-invalid @enderror" id="address" aria-describedby="address"
                    required placeholder="es. Via Delle Alpi 15">
            </div>

            {{-- Descrizione --}}
            <div class="mb-3">
                <label for="description" class="form-label">Descrizione *
                    {{-- error message --}}
                    @error('description')
                        <span class="text-danger"> {{ $errors->first('description') }} </span>
                    @enderror
                    {{-- /error message --}}
                </label>
                <textarea for="description @error('description') is-invalid @enderror" minlength="5" maxlength="200"
                    class="form-control @error('description') is-invalid @enderror" name="description" id="description" rows="3"
                    required placeholder="es. ristorante accogliente in una corte del 700">{{ old('description') }}</textarea>
            </div>
            {{-- Descrizione --}}

            {{-- Tipologia --}}
            <span>Tipologie * </span>
            {{-- errors typologies --}}
            @if (!$errors->first('tipologies'))
                <span id="error-message" class="text-danger" style="display:none;">
                    {{-- non puoi avere 0 tipologie e Non puoi inserire piu di 2 tipologie. --}}
                </span>
            @else
                @error('tipologies')
                    <span class="text-danger"> {{ $errors->first('tipologies') }} </span>
                @enderror
            @endif
            {{-- errors typologies --}}

            <div class="container mb-4">
                <div class="row" role="group" aria-label="Basic checkbox toggle button group">

                    @foreach ($listTypes as $curType)
                        <div class="col-4 btn-group flex flex-wrap mt-3">
                            {{-- @if ('tipologies' !== null) --}}
                            <input type="checkbox" class="btn-check" id="tech-{{ $curType->id }}" name="tipologies[]"
                                value="{{ $curType->id }}" @checked(in_array($curType->id, old('tipologies', [])))>
                            {{-- @endif --}}

                            <label class="btn btn-outline-secondary"
                                for="tech-{{ $curType->id }}">{{ $curType->name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
            {{-- /Tipologia --}}

            {{-- Partita_Iva --}}
            <div class="mb-3">
                <label for="p_iva" class="form-label">Partita Iva *
                    {{-- error message --}}
                    @error('p_iva')
                        <span class="text-danger"> {{ $errors->first('p_iva') }} </span>
                    @enderror
                    {{-- /error message --}}
                </label>
                {{-- <input value="{{ old('p_iva') }}" type="number" name="p_iva" min="11" max="11" step="1" placeholder="es. 12345678901 -> 11 numeri"
                    class="form-control  @error('p_iva') is-invalid @enderror" id="p_iva" aria-describedby="p_iva" required> --}}
                <input value="{{ old('p_iva') }}" type="text" name="p_iva" pattern="^\d{11}$" maxlength="11"
                    placeholder="es. 12345678901 -> 11 numeri" class="form-control @error('p_iva')
                    is-invalid
                    @enderror"
                    id="p_iva" aria-describedby="p_iva" required>
            </div>
            {{-- /Partita_Iva --}}

            {{-- Immagine --}}
            <div class="mb-3">
                <label for="image" class="form-label">Immagine *
                    @error('image')
                        <span class="text-danger">{{ $errors->first('image') }}</span>
                    @enderror
                </label>
                <span id="errorImage" class="text-danger"></span>
                <input value="{{ old('image') }}" type="file" name="image"
                    class="form-control  @error('image') is-invalid
                    @enderror" id="image"
                    aria-describedby="image" required>
            </div>
            {{-- /Immagine --}}

            <div class="container-preview m-auto mt-3">

                {{-- img preview --}}
                <div class="container-preview m-auto mt-3">
                    <div class="mt-2 card-img">
                        <img id="imagePreview" class="hide" src="" alt="new-image">
                    </div>
                </div>
                {{-- /img preview --}}

                {{-- button add and remove --}}
                <div class="container">
                    <div class="row gap-2 mt-3 align-items-center justify-content-center">
                        <button class="btn btn-success col" type="submit">Conferma</button>
                        <a id="btnDelete" class="btn btn-danger col hide">Rimuovi</a>
                    </div>
                </div>
                {{-- /button add and remove --}}

            </div>

        </form>
    </div>

    <script>
        function validateImage(file) {
            return new Promise((resolve, reject) => {
                // Verifica dell'estensione del file
                const allowedExtensions = ['jpg', 'jpeg', 'png'];
                const extension = file.name.split('.').pop().toLowerCase();
                if (!allowedExtensions.includes(extension)) {
                    alert('Tipo di file non valido.');
                    return resolve(false);
                }
                
                // Verifica del tipo MIME
                const allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
                const reader = new FileReader();
                reader.onload = function(event) {
                    const mimeType = event.target.result.match(/:(.*?);/)[1];
                    if (!allowedMimeTypes.includes(mimeType)) {
                        alert('Tipo di file non valido.');
                        return resolve(false);
                    }
                    
                    // Verifica delle dimensioni del file
                    const maxSize = 1024 * 1024; // 1 MB
                    if (file.size > maxSize) {
                        // alert('Il file è troppo grande. Dimensione massima consentita: 1 MB.');
                        return resolve(false);
                    } 

                    return resolve(true);
                };
                reader.readAsDataURL(file);
            });
        }
        
        // Utilizzo
        document.querySelector('#image').addEventListener('change', async function() {
            const isValid = await validateImage(this.files[0]);
            const imgElem = document.getElementById("imagePreview");
            const errImg = document.getElementById("errorImage");
            if (!isValid) {
                console.log(imgElem);
                imgElem.src = "";
                imgElem.classList.add('hide');
                errImg.innerHTML = "Immagine troppo grande";
                this.value = ''; // Ripristina il valore dell'input per rimuovere il file selezionato
            } else {
                imgElem.classList.remove('hide');
                errImg.innerHTML = "";
            }
        });
    </script>
@endsection
