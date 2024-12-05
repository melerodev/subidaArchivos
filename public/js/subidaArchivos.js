const file = document.getElementById('file');
const text = document.getElementById('text');
const button = document.getElementById('button');
// expresion regular para una url de una imagen
const url = /^https?:\/\/.*\.(png|jpg|jpeg|gif)$/;

text.addEventListener('input', () => {
    if (text.value.length > 0) {
        file.disabled = true;
        if (text.value.match(url)) {
            text.style.border = '2px solid green';
            button.disabled = false;
        } else {
            text.style.border = '2px solid red';
            button.disabled = true;
        }
    } else {
        text.style.border = '2px solid #f07e13';
        file.disabled = false;
        button.disabled = false;
    }
});


file.addEventListener('change', () => {
    if (file.files.length > 0) {
        text.disabled = true;
    } else {
        text.disabled = false;
    }
});