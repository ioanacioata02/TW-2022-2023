
form = document.getElementById("add-class");
  form.addEventListener('submit', async (event) => {
      event.preventDefault();
      const formData = new FormData(form);
      const name = formData.get('className');

      console.log(name);
      const token = localStorage.getItem('token');

      const requestBody = JSON.stringify({ className: name });
      console.log(requestBody);
    
  
      try {const token = localStorage.getItem('token');
          const url = `http://localhost/classes`;
          const response = await fetch(url, {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
                  'Authorization': `Bearer ${token}`
              },
              body: requestBody,
          });
  
          if (response.ok) {
            displayMessage('Class created successfully',false);
              console.log('Class created successfully');
              document.location = `MyClasses.html`;
          } else {
            displayMessage('Failed to create class',true);
              console.error('Failed to create class');
          }
      } catch (error) {
          console.error('Error:', error);
      }
  });
  function redirectOnClose()
  {
    document.location = `myClasses.html`;
  }
