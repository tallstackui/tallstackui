export default (model) => ({
  show: false,
  uploading: false,
  progress: 0,
  property: @js($property),
  init() {
    //
  },
  upload() {
    this.uploading = true;
    @this.uploadMultiple(
      this.property, // The property name
      this.$refs.files.files, // The File JavaScript object
      finish = () => this.uploading = false, // Runs when upload is complete...
      error = () => this.uploading = false, // Runs on error...
      progress = (event) => this.progress = event.detail.progress // Updates the progress bar...,
    )
  }
});
