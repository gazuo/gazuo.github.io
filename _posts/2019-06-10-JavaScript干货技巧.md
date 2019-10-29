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

```js
if(typeof module == undefined){
    module.exports = foo;
}
```

 关于 !和 !! 用法
1、!可将变量转换成boolean类型，null、undefined,0和空字符串取反都为true，其余都为false。
 ```js
!null=true

!undefined=true

!''=true

!100=false

!'abc'=false

!0=true
```
2、!!常常用来做类型判断，在第一步!（变量）之后再做逻辑取反运算，在js中新手常常会写这样臃肿的代码：
判断变量a为非空，未定义或者非空串才能执行方法体的内容
```js
var a;
if(a!=null&&typeof(a)!=undefined&&a!=''){
    //a有内容才执行的代码  
}
实际上我们只需要写一个判断表达：
if(!!a){
    //a有内容才执行的代码...  
}

```

可以总结出来，“！”是逻辑与运算，并且可以与任何变量进行逻辑与将其转化为布尔值，“!!”则是逻辑与的取反运算，尤其后者在判断类型时代码简洁高效，省去了多次判断null、undefined和空字符串的冗余代码。