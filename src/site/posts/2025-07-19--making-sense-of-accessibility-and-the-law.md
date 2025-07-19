---
title: Making sense of accessibility and the law
intro: Accessibility isn't just the right thing to do, it’s the law. Here's how the law applies to digital products, in plain English.
date: 2025-07-19
tags:
    - Accessibility
---

The reason to make things accessible to as many people as possible is because it's the *right thing to do*, but the sad fact is that that's rarely how it works.

Where other constraints in product design, like security, are seen as non-negotiables, accessibility is often overlooked as an inconvenient nice-to-have. It often comes as a shock to product managers when the penny drops that accessibility is a legal requirement. Not only have they been exposing their product to huge risk, but they now have to re-do a lot of work, sometimes having to put feature development on hold.

The legal landscape is difficult to wrap your head around so, like my [deliberately over-simplified breakdown of the Web Content Accessibility Guidelines (WCAG)](/blog/wcag-but-in-language-i-can-understand), here's how accessibility is covered by the law.

<i>Note: I'm not a lawyer, and this is surface-level stuff intended to give you a feel for how it all slots together.</i>


## Lots of laws

There are countless laws that govern private sector digital accessibility across the world. Here are the heavy-hitters that I encounter regularly in my day-to-day work:

- [European Accessibility Act (EAA)](#european-accessibility-act-eaa)
- [Individual EU state employment law](#individual-eu-state-employment-law)
- [Equality Act 2010 (United Kingdom)](#equality-act-2010-united-kingdom)
- [Americans with Disabilities Act (ADA)](#americans-with-disabilities-act-ada)

### European Accessibility Act (EAA)

[The EAA](https://eur-lex.europa.eu/eli/dir/2019/882/oj#tit_1) is the new kid on the block, having taken effect on the 28th of June 2025. Some key points:

- Products already on the market on that date have until 2030 to comply
- It applies to any business that sells into the EU, whether they're in an EU state or not
- It only applies to websites and apps that are for 'consumers', not those intended for business use
- 'Microenterprises', defined in Article 3 (23), are exempt

### Individual EU state employment law

That last point in the EAA isn't quite the get-out-of-jail-free card it seems, as business to business (B2B) software is usually covered in legislation on equal opportunity employment in individual EU states, for example:

- Germany's [Sozialgesetzbuch IX, §164](https://www.gesetze-im-internet.de/sgb_9_2018/__164.html)
- France's [Code du travail, Article L5213-6](https://www.legifrance.gouv.fr/codes/article_lc/LEGIARTI000048589854)
- Spain's [Real Decreto Legislativo 1/2013, Artículo 7](https://www.boe.es/buscar/act.php?id=BOE-A-2013-12632#a7)
- Italy's [Decreto Legislativo 151/2015, Legge 68](https://www.normattiva.it/uri-res/N2Ls?urn:nir:stato:legge:1999;68)

### Equality Act 2010 (United Kingdom)

I live in the UK where we've had the Equality Act since 2010. It requires organisations to make sure that their services, including websites, mobile apps, and other digital platforms, are accessible to people with disabilities. [Section 20 (3) says](https://www.legislation.gov.uk/ukpga/2010/15/section/20):

> Where a provision, criterion or practice … puts a disabled person at a substantial disadvantage … in comparison with persons who are not disabled, [the duty is] to take such steps as it is reasonable to have to take to avoid the disadvantage.

It doesn't make a distinction between B2B and direct to consumer (D2C), so if you're getting in the way of disabled people doing what they need to do using the software you produce, you're liable.


### Americans with Disabilities Act (ADA)

Like the UK's Equality Act, the ADA covers both D2C and B2B. It has been around since 1990, which pre-dates the internet, but there have been lots of lawsuits relating to websites and apps in the intervening decades.

Here's what it says about D2C products in [Title III, 42 U.S. Code § 12182(a)](https://www.law.cornell.edu/uscode/text/42/12182):

> No individual shall be discriminated against on the basis of disability in the full and equal enjoyment of the goods, services, facilities, privileges, advantages, or accommodations of any place of public accommodation.

And in [Title I, 42 U.S.C. § 12112(a)](https://www.law.cornell.edu/uscode/text/42/12112) the ADA covers employee discrimination. This means that digital products people use at work must be accessible:

> No covered entity shall discriminate against a qualified individual on the basis of disability in regard … terms, conditions, and privileges of employment.


## The public sector

An honourable mention for the public sector, which tends to have separate laws to govern its accessibility, as you'll definitely have heard of some of these:

- [Section 508](https://www.section508.gov) in the US
- [The Public Sector Bodies Accessibility Regulations 2018](https://www.legislation.gov.uk/uksi/2018/952/made) in the UK
- [Directive (EU) 2016/2102](https://eur-lex.europa.eu/eli/dir/2016/2102/oj/eng)

But if you're not a government organisation, you'll be more interested in complying with the EAA, ADA, etc.


## You can be sued!

So accessibility is the law, but who has actually been sued!? Well, it turns out, lots of companies; here are some high profile examples from the United States where *thousands* of accessibility lawsuits are filed every year:

- [Target (2006)](https://en.wikipedia.org/wiki/National_Federation_of_the_Blind_v._Target_Corp); this was the first major web accessibility lawsuit and defined a website as a "place of public accommodation"
- [Domino’s Pizza (2019)](https://www.boia.org/blog/the-robles-v.-dominos-settlement-and-why-it-matters); the Supreme Court denied review, which reinforced the 2006 application of the ADA to digital services
- [Netflix (2012)](https://dredf.org/nad-v-netflix/); the first online-only product to be held accountable

In the UK and Europe, court cases are culturally much less common; the very real threat of legal action is usually avoided as companies are put under pressure by bodies like the [RNIB](https://www.rnib.org.uk) and usually fix issues before being dragged through the courts in a case they are likely to lose.


## How to comply

If your product is in a tricky spot, accessibility-wise, you could well be in for a lot of bad PR and legal action; how do you put things right?

Meeting [WCAG](https://www.w3.org/TR/WCAG/) version 2.2 level AA is a solid baseline for accessibility in the UK and US. In Europe, [EN 301 549](https://www.etsi.org/deliver/etsi_en/301500_301599/301549/03.02.01_60/en_301549v030201p.pdf) has long been the standard, and the good news is that it's pretty much just WCAG with a few extras, such as ensuring operating systems (like [Dark Mode](/blog/dark-mode-websites-on-macos-mojave), and [Reduce Motion](/blog/reducing-motion)) are respected.

But meeting WCAG/EN 301 549 may still not be enough to avoid legal trouble. [WCAG is notoriously open to interpretation](/blog/erring-on-the-side-of-caution) and even an objectively [conformant website or app can still create barriers](/blog/accessibility-doesnt-stop-at-wcag-compliance); for example, through confusing user journeys.

WCAG is a great first milestone on the way towards accessibility, but a culture of accessible product design and development is the best way to avoid legal issues.
