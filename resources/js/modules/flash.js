    document.addEventListener("DOMContentLoaded", function () {
        // success メッセージ
        let successEl = document.getElementById("flash-success");
        if(successEl){
            setTimeout(function(){
                successEl.style.transition = "opacity 0.8s";
                successEl.style.opacity = "0";
                setTimeout(function(){
                    successEl.remove();
                },800);
            },2500);
        }
        // error メッセージ
        let errorEl = document.getElementById("flash-error");
        if (errorEl) {
            setTimeout(function () {
                errorEl.style.transition = "opacity 0.8s";
                errorEl.style.opacity = "0";
                setTimeout(function () {
                    errorEl.remove();
                }, 800);
            }, 3500);
        }
    });