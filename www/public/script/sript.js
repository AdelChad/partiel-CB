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
