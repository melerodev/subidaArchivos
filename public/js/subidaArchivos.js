const file = document.getElementById('file');
const text = document.getElementById('text');
const button = document.getElementById('button');
// expresion regular para una url de una imagen
const url = /\b((http|https):\/\/)?((www\.)?([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,})(\/[a-zA-Z0-9-._~:\/?#[\]@!$&\'()*+,;=]*)?\b/;

text.addEventListener('input', () => {
    console.log(file);
    if (text.value.length > 0) {
        file.disabled = true;
        if (text.value.match(url)) {
            text.style.border = '2px solid green';
            button.disabled = false;
            button.style.backgroundColor = '#f07e13';
        } else {
            text.style.border = '2px solid red';
            button.disabled = true;
        }
    } else {
        text.style.border = '2px solid #f07e13';
        file.disabled = false;
        button.disabled = false;
        button.style.backgroundColor = '#f5af6e';
    }
});

file.addEventListener('change', () => {
    if (file.files.length > 0) {
        button.disabled = false;
        text.disabled = true;
        button.style.backgroundColor = '#f07e13';
    } else {
        button.disabled = true;
        text.disabled = false;
        button.style.backgroundColor = '#f5af6e';
    }
});

window.onload = () => {
    button.disabled = true;
    button.style.backgroundColor = '#f5af6e';
};