# Footnote Writer

A lightweight Drupal filter that converts simple inline footnote markers into numbered footnotes appended to the end of the content.

## Overview

Footnote Writer provides a minimal syntax for adding footnotes directly inside text without dealing with HTML or manual numbering.

It is designed to:

* reduce friction while writing
* avoid manual footnote management
* integrate cleanly with Drupal’s text format system

This module does one thing:

* transform [fn: ...] into structured, numbered footnotes

## Usage

Write footnotes inline using:

[fn: This is a footnote.]

Multiple footnotes are supported:

This is a sentence.[fn: First note.]

Another sentence.[fn: Second note.]
Output Behavior

The filter will:

replace each [fn: ...] with a numbered marker in the text
collect all footnotes
append them to the end of the content in order

Example (conceptual):

This is a sentence.¹

Another sentence.²
1. First note.
2. Second note.
##Installation

Enable the module as usual:

drush en footnote_writer

On installation, the filter is automatically enabled for:

* Basic HTML
* Full HTML

## Limitations
Nested footnotes are not supported
Complex structures inside footnotes may not render as expected
Interaction with other filters depends on filter order
No UI configuration is provided
