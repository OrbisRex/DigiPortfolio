/* 
 * The MIT License
 *
 * Copyright 2020 Yskejp.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */


(function ($) {
    $.fn.serializeFormJSON = function() {
        let o = {};
        let a = this.serializeArray();
        $.each(a, function () {
            let name = this.name;
            let value = this.value || '';
            if (o[name]) {
                if (!Array.isArray(o[name])) {
                    o[name] = [o[name]];
                }
                o[name].push(value);
            } else {
                o[name] = value;
            }
        });
        return o;
    };
})(jQuery);

$('form').submit(function(e) {
    e.preventDefault();

    let form = $(this);
    let url  = form.attr('action');
    let data = form.serializeFormJSON();
    $.ajax({
        type: 'POST',
        url: url,
        data: data,
        success: function(data) {
           console.log(data);
        },
    });
});