---
title:  JS中小知识
subtitle: 'JavaScript小知识'
author: 王壮壮
layout: post
date: 2019-06-10
categories: [javaScript]
tags: [javaScript]
---
# JS中一些实用小知识

 

1.如何判断一个变量是否已经被定义？例如判断当前环境是否存在"foo"。
   你是否会想到 if(foo) 这种判断？

```js
if(foo){
    console.log("foo存在");
}

if(!foo){
    console.log("foo存在");
}


if(window["foo"] == undefined){
  console.log("foo不存在");
}

```
这样是都会直接报错的。

```js
if(typeof foo !== undefined){
    console.log("foo存在");
}
```
这样就不会错报错。
答案应该使用 typeof 来进行判断，直接使用if 判断如果不存在就会报错未定义 ，但是使用if 和typeof 组合就不会报错了。

应用场景比如判断当前是node环境还是引用环境
node环境需要用到module.exports，但是引用环境不一定有module这个东西
```
if(typeof module == undefined){
    module.exports = foo;
}
```



 
