Dropzone.autoDiscover = false;
let dropzone = new Dropzone('#my-awesome-dropzone',{
    paramName: "file", // The name that will be used to transfer the file
    maxFilesize: 10, // MB
    dictDefaultMessage: "Arrastre aqui para subir evidencias.",
    acceptedFiles: "image/jpg, image/jpeg, image/png, text/plain,  application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.openxmlformats-officedocument.presentationml.presentation, application/pdf, application/vnd.ms-excel, application/vnd.ms-powerpoint, application/msword",
    accept: function(file, done) {
        console.log(file.type);
        var thumbnail = $('.dropzone .dz-preview.dz-file-preview .dz-image:last');
        switch (file.type) {
            case 'text/plain':
                thumbnail.css('background', 'url(/jmdistributions/Imagenes/icon-txt.png) no-repeat scroll center');
                thumbnail.css('background-size', 'contain');
                break;
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': 
            case 'application/vnd.ms-excel':
                thumbnail.css('background', 'url(/jmdistributions/Imagenes/icon-excel.png) no-repeat scroll center');
                thumbnail.css('background-size', 'contain');
                break;
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
            case 'application/msword':
                thumbnail.css('background', 'url(/jmdistributions/Imagenes/icon-word.png) no-repeat scroll center');
                thumbnail.css('background-size', 'contain');
                break;
            case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
            case 'application/vnd.ms-powerpoint':
                thumbnail.css('background', 'url(/jmdistributions/Imagenes/icon-power-point.png) no-repeat scroll center');
                thumbnail.css('background-size', 'contain');
                break;
            case 'application/pdf':
                thumbnail.css('background', 'url(/jmdistributions/Imagenes/icon-pdf.png) no-repeat scroll center');
                thumbnail.css('background-size', 'contain');
                break;
        }
        done();
    }
});

//Remueve todos los archivos de dropzone
$('.reset-dropzone').click(()=>{
    dropzone.removeAllFiles();
});