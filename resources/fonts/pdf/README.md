# PDF fonts

Font definitions for `tc-lib-pdf`, loaded through `K_PATH_FONTS`, which
`App\Services\Pdf\TaggedPdf` points here. PDF/UA requires every font to be
embedded, so the documents cannot fall back to the standard-14 fonts and these
files have to ship with the application.

| Family        | Used for | Source                                                                 | Licence      |
|---------------|----------|------------------------------------------------------------------------|--------------|
| Merriweather  | Headings | <https://github.com/SorkinType/Merriweather>                            | SIL OFL 1.1  |
| Adwaita Sans  | Body     | <https://gitlab.gnome.org/GNOME/adwaita-fonts>                          | SIL OFL 1.1  |

Each family is present as regular, bold, italic and bold-italic; `tc-lib-pdf`
picks the variant from the file name suffix (`b`, `i`, `bi`).

## Regenerating

Adwaita Sans is published as a variable font, so a static instance is cut for
each weight first (`opsz` at the text optical size, `wght` at 400 and 700):

```bash
python3 -m fontTools.varLib.instancer -o src/AdwaitaSans-Regular.ttf \
    AdwaitaSans-Regular.ttf wght=400 opsz=14
python3 -m fontTools.varLib.instancer -o src/AdwaitaSans-Bold.ttf \
    AdwaitaSans-Regular.ttf wght=700 opsz=14
python3 -m fontTools.varLib.instancer -o src/AdwaitaSans-Italic.ttf \
    AdwaitaSans-Italic.ttf wght=400 opsz=14
python3 -m fontTools.varLib.instancer -o src/AdwaitaSans-BoldItalic.ttf \
    AdwaitaSans-Italic.ttf wght=700 opsz=14
```

Merriweather ships static instances, so `Merriweather-Regular.ttf`,
`-Bold.ttf`, `-Italic.ttf` and `-BoldItalic.ttf` can be copied into `src/` as
they are.

The file names decide the font keys: the converter lowercases them and turns
`regular`, `bold` and `italic` into `''`, `b` and `i`, so `Merriweather-Bold.ttf`
becomes `merriweatherb`.

```bash
php vendor/tecnickcom/tc-lib-pdf-font/util/convert.php \
    --outpath=resources/fonts/pdf \
    --type=TrueTypeUnicode \
    --flags=32 \
    --encoding_id=10 \
    --fonts="$(ls -d "$PWD"/src/*.ttf | paste -sd,)"
```

The converter refuses to overwrite an existing definition, so delete the old
`.json`, `.z` and `.ctg.z` files of a family before regenerating it.
