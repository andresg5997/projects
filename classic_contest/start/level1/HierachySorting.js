import Vue from 'vue'
import Data from './csv/file1'

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
          parents = this.checkParents(currentPerson)
          if(parents.length === 0) {
            if(pendingPersons.length !== 0) {
              parents = this.checkParents(pendingPersons[0])
              currentPerson = pendingPersons[0]
              pendingPersons.shift()
              if(parents[1]) pendingPersons.push(parents[1])
              pendingPersons.forEach((remainingPerson, index) => {
              })
            } else {
              existingParents = false
            }
          } else {
            parents.forEach(singleParent => { allAncestors.push(singleParent.name) })
            currentPerson = parents[0]
            if(parents[1]) {
              let sameParent = false
              pendingPersons.forEach(pendingPerson => {
                if(pendingPerson.name === parents[1].name) {
                  sameParent = true
                }
              })
              if(!sameParent) pendingPersons.push(parents[1])
            }
            pendingPersons.forEach((remainingPerson, index) => {
            })
          }
        }
        if(allAncestors.length === 0) allAncestors = 'None'
        this.answer = allAncestors
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
        if(data !== 'None') data.sort()
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
    }
  })
};