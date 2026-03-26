// 1. مصفوفة الأسئلة (يمكنك إضافة أي عدد)

let currentQuestion = 0;
let timeLeft = 30; // إجمالي الوقت بالثواني
const totalTime = 30;
let timerId;

// 2. دالة بدء الامتحان
function startQuiz() {
  renderQuestion();
  startTimer();
}

// 3. عرض السؤال الحالي
function renderQuestion() {
  const q = quizData[currentQuestion];
  document.getElementById("questionCounter").innerText =
    `سؤال ${currentQuestion + 1} من ${quizData.length}`;
  document.getElementById("questionContent").innerText = q.question;

  const optionsContainer = document.getElementById("optionsList");
  optionsContainer.innerHTML = ""; // مسح الخيارات القديمة

  q.options.forEach((opt, index) => {
    const btn = document.createElement("div");
    btn.className = "option-btn";
    btn.innerHTML = `<i class="fa-solid fa-circle"></i> ${opt}`;
    btn.onclick = () => handleAnswer(index, btn);
    optionsContainer.appendChild(btn);
  });
}

// 4. معالجة الإجابة
function handleAnswer(selectedIndex, btnElement) {
  const q = quizData[currentQuestion];

  // منع النقر المتكرر
  const allBtns = document.querySelectorAll(".option-btn");
  allBtns.forEach((b) => (b.style.pointerEvents = "none"));

  if (selectedIndex === q.correct) {
    btnElement.classList.add("correct");
    btnElement.innerHTML = `<i class="fa-solid fa-circle-check"></i> إجابة صحيحة!`;

    setTimeout(() => {
      currentQuestion++;
      if (currentQuestion < quizData.length) {
        renderQuestion();
        // إعادة تفعيل النقر
        const newBtns = document.querySelectorAll(".option-btn");
        newBtns.forEach((b) => (b.style.pointerEvents = "auto"));
      } else {
        showFinalResult();
      }
    }, 1000);
  } else {
    btnElement.classList.add("wrong");
    btnElement.innerHTML = `<i class="fa-solid fa-circle-xmark"></i> حاول مرة أخرى`;
    setTimeout(() => {
      btnElement.classList.remove("wrong");
      btnElement.innerHTML = `<i class="fa-solid fa-circle"></i> ${q.options[selectedIndex]}`;
      allBtns.forEach((b) => (b.style.pointerEvents = "auto"));
    }, 1000);
  }
}

// 5. نظام العداد التنازلي
function startTimer() {
  timerId = setInterval(() => {
    timeLeft--;
    document.getElementById("timerText").innerText = timeLeft;

    // تحديث شريط الوقت
    const percentage = (timeLeft / totalTime) * 100;
    document.getElementById("timerBar").style.width = percentage + "%";

    if (timeLeft <= 0) {
      clearInterval(timerId);
      showTimeout();
    }
  }, 1000);
}

// 6. شاشات النهاية
function showFinalResult() {
  clearInterval(timerId);
  document.getElementById("questionView").style.display = "none";
  document.getElementById("resultView").style.display = "block";
  document.getElementById("successDetails").innerText =
    `لقد أتممت التحدي بنجاح مذهل قبل انتهاء الوقت بـ ${timeLeft} ثانية!`;
}

function showTimeout() {
  document.getElementById("questionView").style.display = "none";
  document.getElementById("timeoutView").style.display = "block";
  document.getElementById("timerBar").style.backgroundColor = "red";
}

// انطلاق!
window.onload = startQuiz;
