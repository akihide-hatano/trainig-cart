window.previewFile = function (input) {
    const file = input.files[0];
    const img = document.getElementById('preview-img');

    if (file) {
        //newFilereaderで情報取得
        const reader = new FileReader();
        reader.onload = function(e) {
            console.log(e);
            img.src = e.target.result;
            img.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    } else {
        img.src = "";
        img.classList.add("hidden");
    }
}