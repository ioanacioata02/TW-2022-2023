function getId() {
    const urlParams = new URLSearchParams(window.location.search);
    const classId = urlParams.get('id');
    return classId;
  }
  form = document.getElementById("add-student");
  form.addEventListener('submit', async (event) => {
      event.preventDefault();
      const formData = new FormData(form);
      const id = formData.get('userId');
      const classId = getId();
      console.log(classId);
      if (!/^\d+$/.test(id) || parseInt(id) < 1 || parseInt(id) > 100000) {
          const errorElement = document.querySelector('.error-message');
          errorElement.textContent = 'Please enter a valid user ID between 1 and 100000';
          return;
      }
      
      const requestBody = JSON.stringify({ userId: id });
      console.log(requestBody);
    
  
      try {const token = localStorage.getItem('token');
          const url = `http://localhost/classes/${classId}`;
          const response = await fetch(url, {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
                  'Authorization': `Bearer ${token}`
              },
              body: requestBody,
          });
  
          if (response.ok) {
            displayMessage('Member added successfully',false);
              console.log('Member added successfully');
              const classId = getId();
              document.location = `MyClasses_teacher.html?classId=${classId}`;
          } else { displayMessage('Failed to add member.No user with the given id!',true);
              console.error('Failed to add member');
          }
      } catch (error) {
          console.error('Error:', error);
      }
  });
  function redirectOnClose()
  {
    const classId = getId();
    document.location = `myClasses_teacher.html?classId=${classId}`;
  }