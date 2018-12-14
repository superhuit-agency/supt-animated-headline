'use strict';

var START_DELAY = 1500;
var REVEAL_PAUSE = 1500;

var title, lines;

var currentIndex = 0;
function timeout(ms) {
  return new Promise(function (resolve) {
    setTimeout(resolve, ms);
  });
}

function reveal() {
  return new Promise(function (resolve) {
    var resolver = function resolver() {
      lines[currentIndex].removeEventListener('transitionend', resolver);
      resolve();
    };

    lines[currentIndex].addEventListener('transitionend', resolver);
    lines[currentIndex].classList.add('is-revealed');
  });
}

function switchActive() {
  lines[currentIndex].classList.remove('is-active');
  currentIndex = (currentIndex + 1) % lines.length;
  lines[currentIndex].classList.add('is-active');
}

function unreveal() {
  return new Promise(function (resolve) {
    var resolver = function resolver() {
      lines[currentIndex].removeEventListener('transitionend', resolver);
      resolve();
    };

    lines[currentIndex].addEventListener('transitionend', resolver);
    lines[currentIndex].classList.remove('is-revealed');
  });
}

function loop() {

  reveal().then(function () {
    timeout(REVEAL_PAUSE).then(function () {
      unreveal().then(function () {
        switchActive();
        timeout(100).then(loop);
      });
    });
  });
}

document.addEventListener("DOMContentLoaded", function () {
  title = document.querySelector('.suah');
  if ( !title ) return;

  lines = Array.from(title.querySelectorAll('.suah__line'));
  setTimeout(loop, START_DELAY);
});