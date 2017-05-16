'use strict';

var sentence = document.querySelector('.sentence');
var h1 = document.querySelector('h1');
var h2 = document.querySelector('h2');

var wordsToArray = function wordsToArray(str) {
  return str.split('').map(function (e) {
    return e === ' ' ? '&nbsp;' : e;
  });
};

function insertSpan(elem, letters, startTime) {
  elem.textContent = '';
  var curr = 0;
  var delay = 20;
  for (var _iterator = letters, _isArray = Array.isArray(_iterator), _i = 0, _iterator = _isArray ? _iterator : _iterator[Symbol.iterator]();;) {
    var _ref;

    if (_isArray) {
      if (_i >= _iterator.length) break;
      _ref = _iterator[_i++];
    } else {
      _i = _iterator.next();
      if (_i.done) break;
      _ref = _i.value;
    }

    var letter = _ref;

    var span = document.createElement('span');
    span.innerHTML = letter;
    span.style.animationDelay = ++curr / delay + (startTime || 0) + 's';
    elem.appendChild(span);
  }
}

insertSpan(h1, wordsToArray(h1.textContent));
insertSpan(h2, wordsToArray(h2.textContent), .5);

document.addEventListener('mousemove', function (e) {
  var xpos = e.layerX || e.offsetX;
  var ypos = e.layerY || e.offsetY;

  var ax = -(window.innerWidth / 2 - xpos) / 20;
  var ay = (window.innerHeight / 2 - ypos) / 10;

  sentence.style.transform = 'rotateY(' + ax + 'deg) rotateX(' + ay + 'deg)';
});