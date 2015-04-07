/*!
 * FileInput Hebrew
 *
 * This file must be loaded after 'fileinput.js'. Patterns in braces '{}', or
 * any HTML markup tags in the messages must not be converted or translated.
 *
 * @see http://github.com/kartik-v/bootstrap-fileinput
 *
 * NOTE: this file must be saved in UTF-8 encoding.
 */
(function ($) {
    "use strict";

    $.fn.fileinput.locales.he = {
        fileSingle: 'קובץ',
        filePlural: 'קבצים',
        browseLabel: 'בחר &hellip;',
        removeLabel: 'הסר',
        removeTitle: 'הסר את הקבצים הנבחרים',
        cancelLabel: 'בטל',
        cancelTitle: 'בטל העלאה',
        uploadLabel: 'העלה',
        uploadTitle: 'העלה קבצים נבחרים',
        msgSizeTooLarge: 'הקובץ "{name}" (<b>{size} KB</b>) חוג ממגבלת גודל הקובץ המקסימלי המותר של <b>{maxSize} KB</b>. אנא נסה את העלאה מחדש!',
        msgFilesTooLess: 'עליך לבחור לפחות <b>{n}</b> {files} להעלאה. אנא נסה את העלאה מחדש!',
        msgFilesTooMany: 'מספר הקבצים הנבחרים להעלאה <b>({n})</b> חורג מהמקסימום המותר של <b>{m}</b>. אנא נסה את העלאה מחדש!',
        msgFileNotFound: 'הקובץ "{name}" לא נמצא!',
        msgFileSecured: 'הגבלות אבטחה מונעות קריאה של הקובץ "{name}".',
        msgFileNotReadable: 'הקובץ "{name}" אינו קריא.',
        msgFilePreviewAborted: 'תצוגה מקדימה בוטלה עבור הקובץ "{name}".',
        msgFilePreviewError: 'התרחשה שגיאה בעת קריאת הקובץ "{name}".',
        msgInvalidFileType: 'סוג קובץ לא חוקי עבור קובץ "{name}". רק "{types}" הינם סוגים חוקיים.',
        msgInvalidFileExtension: 'סיומת קובץ לא חוקית עבור הקובץ "{name}". רק "{extensions}" הינם סיומות מורשות עבור קבצים.',
        msgValidationError: 'שגיאה בהעלאת קובץ',
        msgLoading: 'טוען קובץ {index} מתוך {files} &hellip;',
        msgProgress: 'טוען קובץ {index} מתוך {files} - {name} - {percent}% השולמוd.',
        msgSelected: '{n} קבצים נבחרו',
        msgFoldersNotAllowed: 'גרור ושחרר קבצים בלבד {n} ספריות הוסרו.',
        dropZoneTitle: 'גרור ושחרר קבצים פה &hellip;'
    };

    $.extend($.fn.fileinput.defaults, $.fn.fileinput.locales.he);
})(window.jQuery);