import Cropper from 'cropperjs'
import '../imports/filament-cropper.css'

export default function FilamentCropper(config) {
    return {
        showCropper: false,
        filename: '',
        filetype: '',
        width: config.width,
        height: config.height,
        shape: config.shape,
        statePath: config.statePath,
        aspectRatio: config.aspectRatio,
        appliedAspectRatio: config.aspectRatio,
        rotatable: config.rotatable,
        rotateDegree: config.rotateDegree,
        dragMode: config.dragMode,
        viewMode: config.viewMode,
        zoomable: config.zoomable,

        scales: {
            flipHorizontal: 1,
            flipVertical: 1,
        },

        cropper: null,

        async init() {
            this.$watch('files', async (files) => {
                if (files === null || files[0] === undefined) {
                    return;
                }

                const reader = new FileReader();
                reader.onload = (e) => {
                    this.$refs.cropper.src = e.target.result;

                    setTimeout(() => {
                        this.destroyCropper();
                        this.initCropper();
                    }, 500);
                };

                this.filename = files[0].name;
                this.filetype = files[0].type;
                await reader.readAsDataURL(files[0]);
            });
        },

        destroyCropper() {
            if (this.cropper === null) {
                return;
            }

            this.cropper.destroy();
            this.cropper = null;
        },

        async initCropper() {
            this.cropper = new Cropper(this.$refs.cropper, {
                aspectRatio: this.aspectRatio,
                rotatable: this.rotatable,
                dragMode: this.dragMode,
                viewMode: this.viewMode,
                zoomable: this.zoomable,
                crop(event) {},
            });
        },

        rotateByValue(value) {
            const previousRotate = this.cropper.getImageData().rotate;
            this.cropper.rotate(value - previousRotate);
        },

        resetRotate() {
            let previousRotate = this.cropper.getImageData().rotate;
            previousRotate = (previousRotate) - (previousRotate * 2);
            this.rotateDegree = 0;
            this.cropper.rotate(previousRotate);
        },

        setAspectRatio(ratio) {
            this.cropper.setAspectRatio(ratio);
            this.appliedAspectRatio = ratio;
        },

        flip() {
            this.scales.flipVertical *= -1;
            this.scales.flipHorizontal *= -1;

            this.cropper.scale(this.scales.flipHorizontal, this.scales.flipVertical);
        },

        flipHorizontal() {
            this.scales.flipHorizontal *= -1;
            this.cropper.scale(this.scales.flipHorizontal, this.scales.flipVertical);
        },

        flipVertical() {
            this.scales.flipVertical *= -1;
            this.cropper.scale(this.scales.flipHorizontal, this.scales.flipVertical);
        },

        zoomByValue(value) {
            this.cropper.zoom(value);
        },

        uploadCropperImage(){
            let ref = this;

            this.cropper.getCroppedCanvas({
                maxWidth: ref.width,
                maxHeight: ref.height,
            }).toBlob((croppedImage) => {

                let input = document.getElementById(this.statePath).querySelector('input[name="filepond"]')
                let event = new Event('change');
                let fileName = ref.filename;
                let filetype = ref.filetype;
                let file = new File(
                    [croppedImage],
                    fileName,
                    {type:filetype, lastModified:new Date().getTime()},
                    'utf-8'
                );

                let container = new DataTransfer();
                container.items.add(file);

                input.files = container.files;
                ref.$dispatch("close-modal", {id: "cropper-modal-"+ref.statePath, files: null})
                input.dispatchEvent(event);
            },  ref.filetype);

        }
    }
}
