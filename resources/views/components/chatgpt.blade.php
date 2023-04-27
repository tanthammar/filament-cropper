<div
    x-load-css="[
                'https://unpkg.com/filepond@^4/dist/filepond.css',
                'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css',
            ]"
    x-load-js="[
        'https://unpkg.com/filepond@^4/dist/filepond.js',
        'https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js'
    ]"
    x-data="{
        files: [],
        pond: {},
        container: $refs.filepond,
        initCropper(event) {
            const image = event.target;
            const index = parseInt(image.dataset.index);
            const cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 1,
                crop(event) {
                    const canvas = cropper.getCroppedCanvas();
                    const blob = canvas.toBlob((blob) => {
                        const formData = new FormData();
                        formData.append('file', blob, image.name);
                        formData.append('index', index);
                        // Send the cropped file to the server using AJAX
                        // e.g. axios.post('/upload', formData)
                    });
                },
            });
        },
    }"
    x-init="$nextTick(() => {
    setTimeout(() => {
                    this.pond = FilePond.create(this.container, {
            allowMultiple: true,
            server: {
                process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                  // Simulate server processing delay
                  setTimeout(() => {
                    // Simulate successful server response
                    const serverId = Math.random().toString(36).substring(7);
                    load(serverId);
                    console.info('filepond faking');
                  }, 1000);
            }
          }
        })
        this.pond.on('processfile', (error, file) => {
    // Create a new image element and load the uploaded file into it
    const img = document.createElement('img');
    img.src = URL.createObjectURL(file.file);

    // Wait for the image to load before initializing CropperJS
    img.onload = () => {
      // Initialize CropperJS
      const cropper = new Cropper(img, {
        aspectRatio: 1,
        crop: () => {
        console.info('cropping')
          // Get the cropped image data as a canvas element
          const canvas = cropper.getCroppedCanvas();
          // Convert the canvas element to a blob for uploading
          canvas.toBlob((blob) => {
            // Replace the original file with the cropped blob
            file.setMetadata({ type: blob.type });
            file.setFile(blob, file.filename);
            file.markAsComplete();
            // Remove the CropperJS instance
            cropper.destroy();
          }, file.file.type);
        }
      });
    };
  })
                  }, 2000);


})">
    <input type="file" multiple x-ref="filepond" x-on:change="files = $refs.filepond.files">
    <template x-for="(file, index) in files">
        <div x-bind:key="index">
            <img x-bind:src="URL.createObjectURL(file)" x-bind:data-index="index" x-on:load="initCropper($event)">
        </div>
    </template>
</div>

