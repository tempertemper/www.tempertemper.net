---
title: AVIF and WebP are not always better than PNG and JPG
intro: |
    AVIF and WebP are better image compression smaller files than PNG and JPG, but
date: 2021-05-24
tags:
    - Development
    - Performance
---

In [my post on how good AVIF is](/blog/avif-image-compression-is-incredible), I mention:

> I’ve found the odd occasion where an equivalent quality WebP file ends up slightly bigger than the PNG or JPEG; I’m yet to see that with AVIF.

Dariusz Więckiewicz also found some of his [WebP and AVIF conversions were bigger than the original](https://dariusz.wieckiewicz.org/en/not-so-fast-with-avif-webp/) PNG or JPG they were taken from too. Like Dariusz, I losslessly compress my exported PNGs and JPGs after export (I use [ImageOptim](https://imageoptim.com/mac)) to ensure they're as small as possible. And, it would seem, sometimes that's as good as it'll get!

Here's a quick snapshot of some of the images I converted to WebP and AVIF, along with the difference in file size for each, when compared to their original PNG or JPG:

<section class="table-wrapper" aria-labelledby="caption1" tabindex="0">
    <table>
        <caption id="caption1">Image 1</caption>
        <thead>
            <tr>
                <th>Type</th>
                <th>File size</th>
                <th>Difference</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>PNG</td>
                <td>44KB</td>
                <td>
                    <span aria-hidden="true">-</span>
                    <span class="visually-hidden">Not applicable</span>
                </td>
            </tr>
            <tr>
                <td>WebP</td>
                <td>28KB</td>
                <td>-36%</td>
            </tr>
            <tr>
                <td>AVIF</td>
                <td>19KB</td>
                <td>-57%</td>
            </tr>
        </tbody>
    </table>
</section>

<section class="table-wrapper" aria-labelledby="caption2" tabindex="0">
    <table>
        <caption id="caption2">Image 2</caption>
        <thead>
            <tr>
                <th>Type</th>
                <th>File size</th>
                <th>Difference</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>PNG</td>
                <td>128KB</td>
                <td>
                    <span aria-hidden="true">-</span>
                    <span class="visually-hidden">Not applicable</span>
                </td>
            </tr>
            <tr>
                <td>WebP</td>
                <td>93KB</td>
                <td>-27%</td>
            </tr>
            <tr>
                <td>AVIF</td>
                <td>23KB</td>
                <td>-82%</td>
            </tr>
        </tbody>
    </table>
</section>

<section class="table-wrapper" aria-labelledby="caption3" tabindex="0">
    <table>
        <caption id="caption3">Image 3</caption>
        <thead>
            <tr>
                <th>Type</th>
                <th>File size</th>
                <th>Difference</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>JPG</td>
                <td>51KB</td>
                <td>
                    <span aria-hidden="true">-</span>
                    <span class="visually-hidden">Not applicable</span>
                </td>
            </tr>
            <tr>
                <td>WebP</td>
                <td>35KB</td>
                <td>-31%</td>
            </tr>
            <tr>
                <td>AVIF</td>
                <td>26KB</td>
                <td>-49%</td>
            </tr>
        </tbody>
    </table>
</section>

<section class="table-wrapper" aria-labelledby="caption4" tabindex="0">
    <table>
        <caption id="caption4">Image 4</caption>
        <thead>
            <tr>
                <th>Type</th>
                <th>File size</th>
                <th>Difference</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>JPG</td>
                <td>132KB</td>
                <td>
                    <span aria-hidden="true">-</span>
                    <span class="visually-hidden">Not applicable</span>
                </td>
            </tr>
            <tr>
                <td>WebP</td>
                <td>87KB</td>
                <td>-34%</td>
            </tr>
            <tr>
                <td>AVIF</td>
                <td>25KB</td>
                <td>-81%</td>
            </tr>
        </tbody>
    </table>
</section>

That's a 32% saving across those four images when converted to WebP and a whopping 67% saving for AVIF, but image 5 is where it gets interesting:

<section class="table-wrapper" aria-labelledby="caption5" tabindex="0">
    <table>
        <caption id="caption5">Image 5</caption>
        <thead>
            <tr>
                <th>Type</th>
                <th>File size</th>
                <th>Difference</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>PNG</td>
                <td>17KB</td>
                <td>
                    <span aria-hidden="true">-</span>
                    <span class="visually-hidden">Not applicable</span>
                </td>
            </tr>
            <tr>
                <td>WebP</td>
                <td>25KB</td>
                <td>+47%</td>
            </tr>
            <tr>
                <td>AVIF</td>
                <td>8KB</td>
                <td>-53%</td>
            </tr>
        </tbody>
    </table>
</section>

I still made a good saving on the AVIF, but the WebP ended up nearly 1.5 times *bigger* than the original PNG. So I discarded the WebP, and my HTML reflected that:

```html
<picture>
    <source srcset="image5.avif" type="image/avif" />
    <img src="image5.png" alt="A description of Image 5" width="800" height="450" />
</picture>
```

Similarly, if I converted an image and the AVIF came out at a bigger file size than the PNG or JPG it was converted from, I wouldn't use it.

It's all very well for *me* to say that though, as I don't use many images on my website so I can afford to spend a bit more time converting and adding them manually. For more image-heavy websites the solution would need to be different. Image conversion would probably become part of the build process:

- convert all PNGs and JPGs to WebP and AVIF
- ensure each WebP and AVIF isn't larger than the file it was converted from
- remove them if they're larger
- ensure the generated HTML doesn't reference any images that didn't make the grade

It looks like [the Eleventy Image plugin](https://www.11ty.dev/docs/plugins/image/) could be the answer to that automation as it:

- <q>Never upscales raster images larger than original size</q>
- <q>supports: jpeg, png, webp, avif</q>
- generates the HTML automatically, based on a macro, so if a WebP or AVIF didn't make the grade they won't end up in the markup

I have a couple of projects that would benefit from Eleventy Image, so I'll no doubt write about it at some point. Though I'll be carefully monitoring how it increases the build time, and weighing up whether it's worth adding yet another dependency.

All things told, I feel AVIF and WebP are very much worth adding, but only if they're not bigger than their source files.
