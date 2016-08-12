# Tokenizer
A PHP application to tokenize a text string using a Google advanced search style syntax.

# Features
* +, -, | preceeding text indicate boolean operators
* The first colon in text indicates an index code
* Quotation marks can be used to indicate phrases
* Backslash is the escape character

# Examples
* su:love +ti:life -su:"one + one"
```

array(3) {
  [0]=>
  object(Token)#2 (4) {
    ["prefix":"Token":private]=>
    string(0) ""
    ["index":"Token":private]=>
    string(0) ""
    ["text":"Token":private]=>
    string(0) ""
    ["phrase":"Token":private]=>
    bool(false)
  }
  [1]=>
  object(Token)#3 (4) {
    ["prefix":"Token":private]=>
    string(0) ""
    ["index":"Token":private]=>
    string(0) ""
    ["text":"Token":private]=>
    string(0) ""
    ["phrase":"Token":private]=>
    bool(false)
  }
  [2]=>
  object(Token)#4 (4) {
    ["prefix":"Token":private]=>
    string(0) ""
    ["index":"Token":private]=>
    string(0) ""
    ["text":"Token":private]=>
    string(0) ""
    ["phrase":"Token":private]=>
    bool(false)
  }
}
```
