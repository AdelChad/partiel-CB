function getNameFile() {

    let fileInput =
        document.getElementById('product_fileImg') != null ?
            document.getElementById('product_fileImg'):
            document.getElementById('fds_fileFds');
    if (fileInput.files.length > 0) {
        // Récupérer le nom du fichier sélectionné
        let spanFileName = document.getElementById('input-file-name'),
            spanCloseFile = document.getElementById('input-file-close');

        spanFileName.innerHTML = fileInput.files[0].name;
        spanCloseFile.classList.remove('d-none')

    }
}

function closeFile(){

    let fileInput =
            document.getElementById('product_fileImg') != null ?
                document.getElementById('product_fileImg'):
                document.getElementById('fds_fileFds'),
        spanFileName = document.getElementById('input-file-name'),
        spanCloseFile = document.getElementById('input-file-close');

    if(fileInput.files.length > 0){
        fileInput = null
        spanFileName.innerHTML = '';
    }
    spanCloseFile.classList.add('d-none')

}


document.addEventListener('DOMContentLoaded', function () {
    let formError = document.querySelectorAll('.form-error');
    // console.log(formError);
    formError.forEach(element => {

        if (element.textContent.trim() !== '') {

            let parent = element.parentElement,
                input  = parent.querySelector('input');
            console.log(input)
            input.classList.remove('border-blue-custom')
            input.classList.add('border-error-custom')
            console.log(input)

            input.addEventListener("change", (event) => {
                input.classList.remove('border-error-custom')
                input.classList.add('border-blue-custom')
            });
        }
    })
});