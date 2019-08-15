var app = new Vue({
  el: '#app',
  data: {
    keyCode: '',
    block: {
      a1: 2,
      a2: 0,
      a3: 2,
      a4: 4,
      b1: 2,
      b2: 0,
      b3: 0,
      b4: 0,
      c1: 0,
      c2: 0,
      c3: 0,
      c4: 0,
      d1: 2,
      d2: 0,
      d3: 0,
      d4: 2
    }
  },
  created() {
    // this.addNumber()
  },
  methods: {
    pressArrowLeft() {
      for (var i = 1; i<5; i++) {
        if(this.block['a' + i] != 0) {
          for(var j = i; j >= 1; j--){
            if(j != 1){
              console.log('this.block[\'a' + (j-1) + '\'] : ', this.block['a' + (j-1)])
              console.log('this.block[\'a' + j + '\'] : ', this.block['a' + j])
              if(this.block['a' + (j-1)] == 0){
                this.block['a' + (j-1)] = this.block['a' + j]
                this.block['a' + j] = 0
              }else if(this.block['a' + (j-1)] == this.block['a' + j]){
                this.block['a' + (j-1)] = this.block['a' + (j-1)] * 2;
                this.block['a' + j] = 0;
                break;
              }
            }
          }
        }
        if(this.block['b' + i] != 0) {
          for(var j = i; j >= 1; j--){
            if(j != 1){
              if(this.block['b' + (j-1)] == 0){
                this.block['b' + (j-1)] = this.block['b' + j]
                this.block['b' + j] = 0
              }else if(this.block['b' + (j-1)] == this.block['b' + j]){
                this.block['b' + (j-1)] = this.block['b' + (j-1)] * 2;
                this.block['b' + j] = 0;
                break;
              }
            }
          }
        }
        if(this.block['c' + i] != 0) {
          for(var j = i; j >= 1; j--){
            if(j != 1){
              if(this.block['c' + (j-1)] == 0){
                this.block['c' + (j-1)] = this.block['c' + j]
                this.block['c' + j] = 0
              }else if(this.block['c' + (j-1)] == this.block['c' + j]){
                this.block['c' + (j-1)] = this.block['c' + (j-1)] * 2;
                this.block['c' + j] = 0;
                break
              }
            }
          }
        }
        if(this.block['d' + i] != 0) {
          for(var j = i; j >= 1; j--){
            if(j != 1){
              if(this.block['d' + (j-1)] == 0){
                this.block['d' + (j-1)] = this.block['d' + j]
                this.block['d' + j] = 0
              }else if(this.block['d' + (j-1)] == this.block['d' + j]){
                this.block['d' + (j-1)] *= 2
                this.block['d' + j] = 0;
                break;
              }
            }
          }
        }
      }
    },
    pressArrowUp() {
      for (i = 0; i < 4; i++) {
        console.log('Iteration of I : ', i)
        if(this.block[(i+10).toString(36) + 1] != 0) {
          for(var j = i; j >= 1; j--) {
            console.log('Iteration of J : ', j)
            if((i+10).toString(36) != 'a') {
              console.log('this.block[\'' + (j+9).toString(36) + '1\'] : ', this.block[(j+9).toString(36) + '1'])
              console.log('this.block[\'' + (j+10).toString(36) + '1\'] : ', this.block[(j+10).toString(36) + '1'])
              if(this.block[(j+9).toString(36) + 1] == 0){
                console.log('Movido!');
                this.block[(j+9).toString(36) + 1] = this.block[(j+10).toString(36) + 1]
                this.block[(j+10).toString(36) + 1] = 0
              }else if(this.block[(j+9).toString(36) + 1] == this.block[(j+10).toString(36) + 1]) {
                console.log('Doblado!')
                this.block[(j+9).toString(36) + 1] *= 2
                this.block[(j+10).toString(36) + 1] = 0
                break
              }
            }
          }
        }
        if(this.block[(i+10).toString(36) + 2] != 0) {
          for(var j = i; j >= 1; j--) {
            if((i+10).toString(36) != 'a') {
              if(this.block[(j+9).toString(36) + 2] == 0){
                this.block[(j+9).toString(36) + 2] = this.block[(j+10).toString(36) + 2]
                this.block[(j+10).toString(36) + 2] = 0
              }else if(this.block[(j+9).toString(36) + 2] == this.block[(j+10).toString(36) + 2]) {
                this.block[(j+9).toString(36) + 2] *= 2
                this.block[(j+10).toString(36) + 2] = 0
                break
              }
            }
          }
        }
        if(this.block[(i+10).toString(36) + 3] != 0) {
          for(var j = i; j >= 1; j--) {
            if((i+10).toString(36) != 'a') {
              if(this.block[(j+9).toString(36) + 3] == 0){
                this.block[(j+9).toString(36) + 3] = this.block[(j+10).toString(36) + 3]
                this.block[(j+10).toString(36) + 3] = 0
              }else if(this.block[(j+9).toString(36) + 3] == this.block[(j+10).toString(36) + 3]) {
                this.block[(j+9).toString(36) + 3] *= 2
                this.block[(j+10).toString(36) + 3] = 0
                break
              }
            }
          }
        }
        if(this.block[(i+10).toString(36) + 4] != 0) {
          for(var j = i; j >= 1; j--) {
            if((i+10).toString(36) != 'a') {
              if(this.block[(j+9).toString(36) + 4] == 0){
                this.block[(j+9).toString(36) + 4] = this.block[(j+10).toString(36) + 4]
                this.block[(j+10).toString(36) + 4] = 0
              }else if(this.block[(j+9).toString(36) + 4] == this.block[(j+10).toString(36) + 4]) {
                this.block[(j+9).toString(36) + 4] *= 2
                this.block[(j+10).toString(36) + 4] = 0
                break
              }
            }
          }
        }
      }
    },
    pressArrowRight() {
      for (var i = 4; i>=1; i--) {
        if(this.block['a' + i] != 0) {
          for(var j = i; j < 5; j++){
            if(j != 4){
              if(this.block['a' + (j+1)] == 0){
                this.block['a' + (j+1)] = this.block['a' + j]
                this.block['a' + j] = 0
              }else if(this.block['a' + (j+1)] == this.block['a' + j]){
                this.block['a' + (j+1)] = this.block['a' + (j+1)] * 2;
                this.block['a' + j] = 0;
                break
              }
            }
          }
        }
        if(this.block['b' + i] != 0) {
          for(var j = i; j < 5; j++){
            if(j != 4){
              if(this.block['b' + (j+1)] == 0){
                this.block['b' + (j+1)] = this.block['b' + j]
                this.block['b' + j] = 0
              }else if(this.block['b' + (j+1)] == this.block['b' + j]){
                this.block['b' + (j+1)] = this.block['b' + (j+1)] * 2;
                this.block['b' + j] = 0;
                break
              }
            }
          }
        }
        if(this.block['c' + i] != 0) {
          for(var j = i; j < 5; j++){
            if(j != 4){
              if(this.block['c' + (j+1)] == 0){
                this.block['c' + (j+1)] = this.block['c' + j]
                this.block['c' + j] = 0
              }else if(this.block['c' + (j+1)] == this.block['c' + j]){
                this.block['c' + (j+1)] = this.block['c' + (j+1)] * 2;
                this.block['c' + j] = 0;
                break
              }
            }
          }
        }
        if(this.block['d' + i] != 0) {
          for(var j = i; j < 5; j++){
            if(j != 4){
              if(this.block['d' + (j+1)] == 0){
                this.block['d' + (j+1)] = this.block['d' + j]
                this.block['d' + j] = 0
              }else if(this.block['d' + (j+1)] == this.block['d' + j]){
                this.block['d' + (j+1)] = this.block['d' + (j+1)] * 2;
                this.block['d' + j] = 0;
                break
              }
            }
          }
        }
      }
    },
    pressArrowDown() {
      for (i = 4; i > 0; i--) {
        console.log('Iteration of I : ', i)
        if(this.block[(i+9).toString(36) + 1] != 0) {
          for(var j = i; j < 4; j++) {
            console.log('Iteration of J : ', j)
            if((i+9).toString(36) != 'd') {
              console.log('this.block[\'' + (j+10).toString(36) + '1\'] : ', this.block[(j+10).toString(36) + '1'])
              console.log('this.block[\'' + (j+9).toString(36) + '1\'] : ', this.block[(j+9).toString(36) + '1'])
              if(this.block[(j+10).toString(36) + 1] == 0){
                console.log('Movido!');
                this.block[(j+10).toString(36) + 1] = this.block[(j+9).toString(36) + 1]
                this.block[(j+9).toString(36) + 1] = 0
              }else if(this.block[(j+10).toString(36) + 1] == this.block[(j+9).toString(36) + 1]) {
                console.log('Doblado!')
                this.block[(j+10).toString(36) + 1] *= 2
                this.block[(j+9).toString(36) + 1] = 0
                break
              }
            }
          }
        }
        if(this.block[(i+9).toString(36) + 2] != 0) {
          for(var j = i; j < 4; j++) {
            if((i+9).toString(36) != 'd') {
              if(this.block[(j+10).toString(36) + 2] == 0){
                this.block[(j+10).toString(36) + 2] = this.block[(j+9).toString(36) + 2]
                this.block[(j+9).toString(36) + 2] = 0
              }else if(this.block[(j+10).toString(36) + 2] == this.block[(j+9).toString(36) + 2]) {
                this.block[(j+10).toString(36) + 2] *= 2
                this.block[(j+9).toString(36) + 2] = 0
                break
              }
            }
          }
        }
        if(this.block[(i+9).toString(36) + 3] != 0) {
          for(var j = i; j < 4; j++) {
            if((i+9).toString(36) != 'd') {
              if(this.block[(j+10).toString(36) + 3] == 0){
                this.block[(j+10).toString(36) + 3] = this.block[(j+9).toString(36) + 3]
                this.block[(j+9).toString(36) + 3] = 0
              }else if(this.block[(j+10).toString(36) + 3] == this.block[(j+9).toString(36) + 3]) {
                this.block[(j+10).toString(36) + 3] *= 2
                this.block[(j+9).toString(36) + 3] = 0
                break
              }
            }
          }
        }
        if(this.block[(i+9).toString(36) + 4] != 0) {
          for(var j = i; j < 4; j++) {
            if((i+9).toString(36) != 'd') {
              if(this.block[(j+10).toString(36) + 4] == 0){
                this.block[(j+10).toString(36) + 4] = this.block[(j+9).toString(36) + 4]
                this.block[(j+9).toString(36) + 4] = 0
              }else if(this.block[(j+10).toString(36) + 4] == this.block[(j+9).toString(36) + 4]) {
                this.block[(j+10).toString(36) + 4] *= 2
                this.block[(j+9).toString(36) + 4] = 0
                break
              }
            }
          }
        }
      }
    },
    addNumber() {
      var emptyBlocks = [];
      for(var position in this.block){
        if(this.block[position] == 0) {
          emptyBlocks.push(position)
        }
      }
      var randomEmptyBlock = Math.floor(Math.random() * emptyBlocks.length);
      var fourOrTwoRandomizer = Math.floor(Math.random() * 3);
      if(fourOrTwoRandomizer == 0) {
        this.block[emptyBlocks[randomEmptyBlock]] = 4;
      }
      else {
        this.block[emptyBlocks[randomEmptyBlock]] = 2;
      }
    }
  }
});

window.addEventListener('keydown', (e) => {
  app.keyCode = e.keyCode
  if((app.keyCode == 37) || (app.keyCode == 38) || (app.keyCode == 39) || (app.keyCode == 40)) {
    if(app.keyCode == 37){
      console.log('LEFT!')
      app.pressArrowLeft()
    }
    if(app.keyCode == 38){
      console.log('UP!')
      app.pressArrowUp()
    }
    if(app.keyCode == 39){
      console.log('RIGHT!')
      app.pressArrowRight()
    }
    if(app.keyCode == 40){
      console.log('DOWN!')
      app.pressArrowDown()
    }
    app.addNumber()
  }
})