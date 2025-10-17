(function init() {
  const modalShowGetCode = document.getElementById("showInstructGetCode");
  const modalShowContent = document.getElementById("showContentOfAds");
  const btnShowInstruction = document.getElementById("btn-show-info");
  if (btnShowInstruction) {
    btnShowInstruction.addEventListener('click', function () {
      const formInstruct = document.getElementById('form-input-code');
      formInstruct.style.display = "block"
    });
  }

  function openModal() {
    modalShowGetCode.style.display = "block";
  }

  function closeModal() {
    modalShowGetCode.style.display = "none";
  }

  window.onclick = function (event) {
    if (event.target == modalShowGetCode) {
      modalShowGetCode.style.display = "none";
    }
    if (event.target == modalShowContent) {
      modalShowContent.style.display = "none";
    }
  }

  /** Start implement for show modal of the result content */
  function openModalShowContent() {
    modalShowContent.style.display = "block";
  }

  function closeModalShowContent() {
    modalShowContent.style.display = "none";
  }
  /** End implement for the modal content */

  const modalGetCode = document.getElementById('openModalGetCode');
  if (modalGetCode) {
    modalGetCode.addEventListener('click', function () {
      openModal();
    });
  }

  const closeModalGetCode = document.getElementById('closeModalGetCode');
  if (closeModalGetCode) {
    closeModalGetCode.addEventListener('click', function () {
      closeModal();
    });
  }

  const closeShowContentModal = document.getElementById('closeModalShowContent');
  if (closeShowContentModal) {
    closeShowContentModal.addEventListener('click', function () {
      closeModalShowContent();
    });
  }

  const btnCopy = document.getElementById('btn-copy')
  if (btnCopy) {
    btnCopy.addEventListener('click', function (e) {
      e.preventDefault();
      const ads_code = document.getElementById('ads_code')
      ads_code.select()
      ads_code.setSelectionRange(0, 99999)
      navigator.clipboard.writeText(ads_code.value)
      alert("Copied: " + ads_code.value)
    });
  }

  const submitCode = document.getElementById('submit-code')
  if (submitCode) {
    submitCode.addEventListener('click', function (e) {
      e.preventDefault();
      const code = document.getElementById('code_content').value;
      const hashed = document.getElementById('hashed').value;
      const url = document.getElementById('app-url').value;
      fetch(url + '/api/seo/ads/check-code', {
        method: "POST",
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          code,
          hashed,
        })
      })
        .then(res => res.json())
        .then(response => {
          const error = document.getElementById('error_code');
          if (response.statusCode >= 400) {
            error.style.display = 'inline-block'
            error.innerHTML = response.message
            document.getElementById('code_content').classList.add('invalid')
          } else {
            error.style.display = 'none'
            // Handle show content of ads
            document.getElementById('content-result').innerHTML = response.content
            document.getElementById('code_content').classList.remove('invalid')
            openModalShowContent()
          }
        })
        .catch(err => {
          console.log({ err });
          const error = document.getElementById('error_code');
          error.style.display = 'inline-block'
          error.innerHTML = 'Invalid code'
        })
    });
  }

  const trafficCountdown = document.getElementById('traffic-countdown')
  if (trafficCountdown) {
    trafficCountdown.addEventListener('click', (e) => {
      trafficCountdown.setAttribute('disabled', 'disabled');
      const timeWaitings = document.getElementById('time-waiting').value
      countDownTimeWaiting(Math.round(parseInt(timeWaitings)))
    })
  }

  function countDownTimeWaiting(seconds) {
    const timeInterval = setInterval(() => {
      seconds--;
      document.getElementById('time-down').innerHTML = `sau ${seconds}`;
      if (seconds === 0) {
        const code = document.getElementById('code-string').value
        const hashedCode = atob(atob(atob(atob(atob(code)))))
        document.getElementById('time-down').innerHTML = ': ' + hashedCode
        clearInterval(timeInterval)
        trafficCountdown.removeAttribute('disabled');
      }
    }, 1000)
  }
})()
