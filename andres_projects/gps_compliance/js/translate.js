
function googleTranslateElementInit() {
  new google.translate.TranslateElement(
    {
      pageLanguage: 'ca',
      includedLanguages: 'ca,de,en,es,fr,ga,it,ja,ko,no,pl,pt,ru,sv,th,zh-TW',
      layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
      autoDisplay: false
    },
    'google_translate_element'
  );
}