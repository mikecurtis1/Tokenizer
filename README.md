# Tokenizer

A lightweight PHP tokenizer for parsing search-style query strings into structured token objects.

Inspired by advanced search syntax used in library systems and search engines, this project explores how raw command-line-style input can be interpreted, normalized, and transformed into machine-friendly data structures.

---

## Overview

This project was built as a focused experiment in parsing and tokenization—specifically, how a compact query language (similar to Google advanced search or library OPAC syntax) can be broken down into meaningful components.

Rather than executing a search, the tokenizer produces structured `Token` objects that could be passed to another system (e.g., a search engine, filter builder, or query interpreter).

---

## Features

- **Boolean-style prefixes**
  - `+` include
  - `-` exclude
  - `|` optional / OR

- **Field / index targeting**
  - The first unescaped `:` splits a token into:
    - `index` (field)
    - `text` (value)

- **Phrase handling**
  - Double quotes (`"`) group terms into exact phrases

- **Escape support**
  - Backslash (`\`) escapes special characters

- **Whitespace normalization**
  - Collapses and trims input for consistent parsing

---

## Example

### Input

su:love +ti:life -su:"one + one"


### Output

```php
array(3) {
  [0]=>
  object(Token) {
    prefix: ""
    index: "su"
    text: "love"
    phrase: false
  }
  [1]=>
  object(Token) {
    prefix: "+"
    index: "ti"
    text: "life"
    phrase: false
  }
  [2]=>
  object(Token) {
    prefix: "-"
    index: "su"
    text: "one + one"
    phrase: true
  }
}

## Token Structure

Each parsed token is represented as a Token object:

* prefix — boolean operator (+, -, |, or empty)
* index — field identifier (e.g., su, ti)
* text — the actual search value
* phrase — whether the text was quoted

## How It Works

The tokenizer processes input in several stages:

1. Normalize whitespace
2. Protect quoted phrases from splitting
3. Split input into raw tokens
4. Parse each token into components:
  * prefix
  * index
  * text
  * phrase flag
5. Clean and unescape values

Special care is taken to correctly handle escaped characters and delimiters inside quoted phrases.

## Why This Exists

This was not built for production use, but as a practical exploration of:

* Parsing strategies in PHP
* Tokenization concepts from information retrieval
* Translating human-friendly syntax into structured data

The approach is intentionally simple and self-contained, with an emphasis on readability and step-by-step transformation.

## Possible Extensions

* Support for nested expressions or parentheses
* Conversion into SQL or search engine queries
* Configurable operators and delimiters
* Integration with a real search backend

## Notes

* Operators and parsing rules are currently hardcoded but could be externalized to configuration
* Designed for clarity over performance or completeness
* Suitable as a teaching or experimentation tool
