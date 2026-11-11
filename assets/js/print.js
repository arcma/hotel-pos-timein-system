// function convertFontArrToStyle(fonts) {
//     const fontFormats = {
//         'ttf': 'truetype',
//         'otf': 'opentype',
//     };
//     let fontsStyle = '';

//     _.each(fonts, (font) => {
//         const fontExt = _.get(font, 'fileName', _.get(font, 'url', '')).split('.').pop().toLowerCase();
//         const fontFormat = _.get(fontFormats, fontExt)
//         fontsStyle += `
//             @font-face{
//                 font-family: ${ _.get(font, 'familyName', '') };
//                 src: url("${ _.get(font, 'url', '') }")${ fontFormat ? ` format("${ fontFormat }")` : '' };
//             }
//         `;
//     });

//     return fontsStyle;
// }

/*
when making changes in this file and pushing it to MASTER branch.
please also update the file in the CDN Assets folder.
 If the assets location setting is set to CDN
*/

function escapeHTML(unsafeText) {
    let div = document.createElement('div');
    div.innerText = unsafeText;
    return div.innerHTML;
}

function getDefaultStyles(config, showBorders = false) {
    const borderStyles = `
        .layout-container p {
            border: 1px solid blue;
        }

        .layout-container div {
            border: 1px solid red;
        }

        .layout-container span:not(:empty),
        .layout-container strong:not(:empty) {
            border: 1px solid green;
        }
    `;

    const printDoubleSided = (config?.printDoubleSided == '1');

    const printWidth = (config?.printWidth || '0') + (config?.printWidthUnit || 'in');
    let printHeight = (config?.printHeight || '0') + (config?.printHeightUnit || 'in');
    printHeight = printDoubleSided ? `calc(${ printHeight } * 2)` : printHeight;

    return `
        <style>
            @page{
                size: ${ printWidth } ${ printHeight };
                margin: 0;
            }
            * {
                box-sizing: border-box;
                margin: 0;
            }
            body{
                font-size: 10pt;
                color: black;
                margin: 0;
            }
            .layout-container {
                display: inline-block;
                width: ${ printWidth };
                height: ${ printHeight };
            }

            ${ showBorders ? borderStyles : '' }
        </style>
    `;
}

function renderPrintTemplate(config, layers, participantData) {
    // Page Wrapper
    const pageWrapperEl = $('<div class="page-wrapper"></div>')
        .css({
            display: 'inline-block',
            position: 'relative',
            width: (config?.printWidth || '0') + (config?.printWidthUnit || 'in'),
            height: (config?.printHeight || '0') + (config?.printHeightUnit || 'in'),
        });

    // QR Code
    if (layers?.qrCode?.show == '1') {
        const qrCodeEl = $(`<img src="${ participantData?.qrCode }" alt="QR Code">`)
            .css({
                position: 'absolute',
                top: (layers?.qrCode?.yOffset || 0) + 'px',
                left: (layers?.qrCode?.xOffset || 0) + 'px',
                width: (layers?.qrCode?.size || 0) + 'px',
                height: (layers?.qrCode?.size || 0) + 'px',
            });
        pageWrapperEl.append(qrCodeEl);
    }

    // Details Container
    const detailsContainerEl = $('<div class="details-container"></div>')
        .css({
            overflow: (layers?.detailsContainer?.overflow || 'unset'),
            fontFamily: (layers?.detailsContainer?.font || 'unset'),

            position: 'absolute',
            top: (layers?.detailsContainer?.yOffset || 0) + 'px',
            left: (layers?.detailsContainer?.xOffset || 0) + 'px',
            width: (layers?.detailsContainer?.width || 0) + 'px',
            height: (layers?.detailsContainer?.height || 0) + 'px',

            display: 'flex',
            flexDirection: 'column',
            justifyContent: ((layers?.detailsContainer?.alignment || '').split('-')[0] || 'unset'),
            textAlign: ((layers?.detailsContainer?.alignment || '').split('-')[1] || 'unset'),
        });
    pageWrapperEl.append(detailsContainerEl);

    // Details Container - Rows
    for (const [key, row] of Object.entries(layers?.detailsContainer?.rows || {})) {
        const rowStyle = {
            marginBottom: (row?.marginBottom || 0) + 'px',
            fontSize: (row?.fontSize || 0) + 'px',
        };

        if (row?.column == 'text') {
            let text = row?.text || '';

            for (const [paColumn, value] of Object.entries(participantData)) {
                text = text.replaceAll(`[${paColumn}]`, value);
            }

            const detailsRowEl = $(`<p>${ text }</p>`).css(rowStyle);
            detailsContainerEl.append(detailsRowEl);
            continue;
        }

        const detailsRowEl = $(`<p>${ (participantData[row?.column] || '') }</p>`).css(rowStyle);
        detailsRowEl.prepend(`<strong class="prefix">${ escapeHTML(row?.prefix || '') }</strong>`);
        detailsRowEl.append(`<span class="suffix">${ escapeHTML(row?.suffix || '')}</span>`);
        detailsContainerEl.append(detailsRowEl);
    }

    // Compile Print Layout
    const layoutContainerEl = $('<div class="layout-container"></div>');

    layoutContainerEl.append(pageWrapperEl);

    if (config?.printDoubleSided == '1') {
        const rotatedPageWrapperEl = pageWrapperEl.clone().css({ transform: 'rotate(180deg)' });
        layoutContainerEl.append(rotatedPageWrapperEl);
    }

    return $('<div/>').append(layoutContainerEl).html();
}

function startPrint(params) {
    const callback = typeof (params?.callback) === 'function'
        ? (params?.callback)
        : (function() {});
    const showBorders = (params?.showBorders || false);
    const closeOnPrint = (typeof params?.closeOnPrint === 'boolean' ? params?.closeOnPrint : true);
    const config = (params?.config || {});
    const layers = (params?.layers || {});
    const participantData = (params?.participantData || {});

    // generate template
    const printLayoutHtml = renderPrintTemplate(config, layers, participantData);

    // open new window and load the template
    const mywindow = window.open('', 'Print ERLDS');
    mywindow.document.write(`
        <html>
            <head>
                <title></title>
                ${ getDefaultStyles(config, showBorders) }
            </head>
            <body>
                ${ printLayoutHtml }
            </body>
        </html>
    `);


    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10

    mywindow.onload = function() {
        mywindow.print();
        if (closeOnPrint) {
            mywindow.close();
        }
        callback();
    };
}

/*
when making changes in this file and pushing it to MASTER branch.
please also update the file in the CDN Assets folder.
 If the assets location setting is set to CDN
*/