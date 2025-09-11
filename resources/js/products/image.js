window.previewFile = function (input) {
    const file = input.files[0];
    const img = document.getElementById('preview-img');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            img.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    } else {
        img.src = "";
        img.classList.add("hidden");
    }
}