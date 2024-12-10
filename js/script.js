const modal = document.getElementById('upload-modal');
    const openModalBtn = document.getElementById('open-modal-btn');
    const closeModalBtn = document.getElementById('close-modal-btn');

    openModalBtn.addEventListener('click', () => {
      modal.classList.add('active');
    });

    closeModalBtn.addEventListener('click', () => {
      modal.classList.remove('active');
    });

    window.addEventListener('click', (e) => {
      if (e.target === modal) {
        modal.classList.remove('active');
      }
    });