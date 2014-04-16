#!/usr/bin/env python

tmpl = open("style.tmpl")

tmplstr = tmpl.read()

schema = { 
    "bright"     : "#1B9898",
    "light"      : "#24CACA",
    "bright_alt" : "#3B8686",
    "dark"       : "#2E457B",
    "pale_dark"  : "#43307F",
    "greysh"     : "#246E6E",
    "white"      : "#FFFFFF",
}

print tmplstr % schema

