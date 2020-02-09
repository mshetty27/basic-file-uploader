const backendUrl = '/backend/process-upload.php';
const form = document.querySelector('form');

form.addEventListener('submit', e => {
  e.preventDefault();

  const files = document.querySelector('[type=file]').files;
  const formData = new FormData();

  for(let i=0; i < files.length; i++){
    formData.append('files[]', files[i]);
  }

  fetch(backendUrl, {
    method: 'POST',
    body: formData
  }).then(response => {
    console.log(response);
    return response.text();
  }).then(html => {
    console.log(html);
  });


});
