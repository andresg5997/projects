import Vue from 'vue'
import Data from './csv/file1'
import { log } from 'util';

window.onload = function () {
  new Vue({
    el: '#app',
    data() {
      return {
        allPersons: '',
        selectedPerson: {},
        answer: ''
      }
    },
    created() {
      this.fetchData()
      // this.runFirst()
      // this.runSecond()
      this.runThird()
    },
    methods: {
      fetchData() {
        let allPersons = Data.split('|')
        let hierachy = []
        let singlePerson = {}
        allPersons.forEach(person => {
          let personArray = person.split(',')
          singlePerson = {
            name: personArray[0],
            father: personArray[1],
            mother: personArray[2],
          }
          hierachy.push(singlePerson)
          singlePerson = {}
        });
        this.allPersons = hierachy
      },
      selectPerson(person) {
        this.selectedPerson = person
      },
      ancestors(preselectedName, click) {
        if(preselectedName) this.selectedPerson = this.allPersons.find(person => person.name === preselectedName)
        let allAncestors = []
        let existingParents = false
        let currentPerson = this.selectedPerson
        let parents = []
        let pendingPersons = []

        if(currentPerson.father) existingParents = true
        if(currentPerson.mother) existingParents = true
        
        while(existingParents) {
          // console.log("--------------------------------");
          // console.log("currentPerson: ", currentPerson.name)
          // console.log("--------------------------------");
          parents = this.checkParents(currentPerson)
          if(parents.length === 0) {
            if(pendingPersons.length !== 0) {
							// console.log("NO parent")
              parents = this.checkParents(pendingPersons[0])
							// console.log("first.pendingPerson", JSON.stringify(pendingPersons[0]))
              // parents.forEach(singleParent => {
                // console.log('single: ', JSON.stringify(singleParent));
              //   allAncestors.push(singleParent.name)
              // })
              currentPerson = pendingPersons[0]
              pendingPersons.shift()
              if(parents[1]) pendingPersons.push(parents[1])
              // console.log('pendingPersons.length: ', pendingPersons.length)
              pendingPersons.forEach((remainingPerson, index) => {
                // console.log(index + '. ' + JSON.stringify(remainingPerson))
              })
            } else {
              existingParents = false
            }
          } else {
            parents.forEach(singleParent => { allAncestors.push(singleParent.name) })
            currentPerson = parents[0]
            // console.log("TCL: ancestors -> parents[1]", JSON.stringify(parents[1]))
            if(parents[1]) {
              let sameParent = false
              pendingPersons.forEach(pendingPerson => {
                if(pendingPerson.name === parents[1].name) {
                  sameParent = true
                }
              })
              if(!sameParent) pendingPersons.push(parents[1])
            }
            // console.log('pendingPersons.length: ', pendingPersons.length)
            pendingPersons.forEach((remainingPerson, index) => {
              // console.log(index + '. ' + JSON.stringify(remainingPerson))
            })
          }
        }
        if(allAncestors.length === 0) allAncestors = 'None'
        this.answer = allAncestors
				// console.log("TCL: ancestors -> allAncestors", allAncestors)
        if(click) this.saveFile(this.parseArrayToString(allAncestors))
        return allAncestors
      },
      checkParents(person) {
        let parents = []
        if(person.father) parents.push(this.getFather(person))
        if(person.mother) parents.push(this.getMother(person))
        return parents
      },
      getFather(person) {
        return this.allPersons.find(possibleFather => person.father === possibleFather.name)
      },
      getMother(person) {
        return this.allPersons.find(possibleMother => person.mother === possibleMother.name)
      },
      parseArrayToString(data) {
				// console.log("TCL: parseArrayToString -> data", data)
        if(data !== 'None') data.sort()
				// console.log("TCL: parseArrayToString -> data", data)
        data = JSON.stringify(data).replace(/[\[\]"]+/g, '');
        return data
      },
      saveFile(dataToSave) {
        const data = JSON.stringify(dataToSave)
        const blob = new Blob([data], {type: 'text/plain'})
        const e = document.createEvent('MouseEvents'),
        a = document.createElement('a');
        a.download = "test.txt";
        a.href = window.URL.createObjectURL(blob);
        a.dataset.downloadurl = ['text/json', a.download, a.href].join(':');
        e.initEvent('click', true, false, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
        a.dispatchEvent(e);
      },
      runFirst() {
        // this.ancestors('Abel')
        this.saveFile(this.parseArrayToString(this.ancestors('Abel')))
      },
      runSecond() {
        console.log('runnig second');
        
        let ancentorsFileText = ''
        ancentorsFileText += this.parseArrayToString(this.ancestors('Cathy'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Kay'))
        this.saveFile(ancentorsFileText)
      },
      runThird() {
        console.log('runnig third');

        let ancentorsFileText = ''
        ancentorsFileText += this.parseArrayToString(this.ancestors('Birgit'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Astrid'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Franz'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Blair'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Lucia'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Nina'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Lewis'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Craig'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Felix'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Buck'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Charly'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Nigel'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Hugo'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Alex'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Hilda'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Lara'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Helen'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Lara'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Jimmy'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Ariana'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Greti'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Nigel'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Bud'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Bruce'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Molly'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Ian'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Ian'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Buck'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Agatha'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Molly'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Steve'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Buck'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Hans'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Uschi'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Ben'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Cathy'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Bonnie'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Graham'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Lewis'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Lorelei'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Karl'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Lorelei'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Lucia'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Lane'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Ingrid'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('George'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Franz'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Blair'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Astrid'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Amber'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Earl'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Boris'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Astrid'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Cathy'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('George'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Anita'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Ingrid'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Nancy'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Lara'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Anita'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Ian'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Conan'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Hilda'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Kyla'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Nancy'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Cheryl'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Nina'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('April'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Hans'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Hazel'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Steve'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Lorelei'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Uschi'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Joan'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Aaron'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Lorelei'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Hazel'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Barry'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Nancy'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Bonnie'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Grace'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Jarno'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Franz'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Edwin'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Kyla'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Donna'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Gilda'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Franz'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Agnes'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Erika'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Abel'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Joan'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Nancy'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Lee'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Abel'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Kyla'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Jarno'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Earl'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Karl'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Olaf'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Barry'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Jarno'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Kyla'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Bruce'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Ariana'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Herbie'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Anita'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Agatha'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Ivan'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Ivy'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Donna'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Lucia'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Erika'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Uschi'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Graham'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Edwin'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Barry'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Nancy'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Astrid'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Jed'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Regi'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Hans'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Ulla'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Lane'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Paul'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Jarno'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Cynthia'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Ulla'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Cheryl'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Anton'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Buck'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Hardy'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Agatha'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Denise'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Cathy'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Felix'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Aaron'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Howard'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Iris'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Ulla'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Craig'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Gilda'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Edwin'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Ulla'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Hector'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Aaron'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Will'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Astrid'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Earl'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Charly'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Greti'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Denise'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Paul'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Blair'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Lorelei'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Greti'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Conan'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Kay'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Astrid'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Lewis'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Anton'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Lane'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Graham'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Bruno'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Lara'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Jimmy'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Nancy'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Flora'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Lee'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Kirsten'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Ariana'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Carmen'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Harald'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Nina'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Hilda'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Lane'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Lara'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Hector'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Buck'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Irina'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Will'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Herbie'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Anita'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Kay'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Felix'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Glenn'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Boris'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Franz'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Alex'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Carmen'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Cynthia'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Kirsten'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Astrid'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Bud'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Conan'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Brad'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Ben'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Keene'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Flora'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Ulla'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Howard'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Birgit'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Jarno'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Anna'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Ulla'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Nigel'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Ben'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Bonnie'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Irina'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Anton'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Will'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Buck'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Brad'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Franz'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Jimmy'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Charly'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Edwin'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Graham'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Ulla'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Abel'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Keene'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Hazel'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Lorelei'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Lucia'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Donna'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Ariana'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Kay'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Donna'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Abel'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Ian'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Bruce'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('April'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Cheryl'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Glenn'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Hardy'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Howard'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Paul'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Ingrid'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Bruce'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Lee'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Astrid'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Regi'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Barry'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Anna'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Ariana'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Ben'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Jimmy'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Ivy'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Livia'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Ulla'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Elton'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Jacob'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Erika'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Mabel'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Felix'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Emily'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Owen'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Denise'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Anton'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Graham'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Jane'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Ivan'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Charly'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Keith'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Igor'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Lewis'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Kayla'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Blair'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Franz'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Nico'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Kelvin'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Bud'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Conan'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Lane'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Maia'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Gabriel'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Becky'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Hugo'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Regi'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Buck'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Rob'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Ivan'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Buck'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Ben'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Grant'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Brad'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Susi'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Keene'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Clyde'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Ivan'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Greti'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Gideon'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Lacey'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Toni'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Edwin'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Carmen'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Lara'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Gabriel'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Grace'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Herbert'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Denise'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Eda'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Irina'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Barry'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Lane'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Edith'))
        ancentorsFileText += '\n'
        ancentorsFileText += this.parseArrayToString(this.ancestors('Nina'))
        ancentorsFileText += '\n'
        this.saveFile(ancentorsFileText)
      },
    }
  })
};