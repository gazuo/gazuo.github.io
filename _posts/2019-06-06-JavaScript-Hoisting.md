---
title:  JS中作用域和变量提升(hoisting)的深入理解
subtitle: 'JavaScript hoisting'
author: 王壮壮
layout: post
date: 2019-06-04
categories: [javaScript]
tags: [javaScript]
---
# JS中作用域和变量提升(hoisting)的深入理解

写Js也写了挺久了。但是很多基础知识还是挺差的。

废话不多说直接来看一段代码

```js
var str = "hello world";
function f() {
    console.log(str);
    if(false){
    	var str = "hello";    
    }
}
f();
```

很多人会说是**hello world**,但是结果是 **undefined,**为什么会这样？这种就引出一个概念--hoisting

var hoisting
    Because variable declarations (and declarations in general) are processed before any code is executed, declaring a variable anywhere in the code is equivalent to declaring it at the top. This also means that a variable can appear to be used before it's declared. This behavior is called "hoisting", as it appears that the variable declaration is moved to the top of the function or global code.

这段话翻译下来就是

​    因为变量申明是在任意代码执行前处理的，在代码区中任意地方申明变量和在最开始（最上面）的地方申明是一样的。也就是说，看起来一个变量可以在申明之前被使用！这种行为就是所谓的“hoisting”，也就是变量提升，看起来就像变量的申明被自动移动到了函数或全局代码的最顶上。

**注意：**仅仅是申明提升了，定义并不会被提升。

所以，上面那段代码其实就是下面的形式:

```js
var str = "hello world";
function f() {
    var str;
    console.log(str);
    if(false){
    	var str = "hello";   
    }
}
f();
```

####声明、定义与初始化
声明宣称一个名字的存在，定义则为这个名字分配存储空间，而初始化则是为名字分配的存储空间赋初值。
用C++来表述这三个概念

extern int i;//这是声明，表明名字i在某处已经存在了
int i;//这是声明并定义名字i,为i分配存储空间
i = 0;//这是初始化名字i,为其赋初值为0
javascript中则是这样

var v;//声明变量v
v = "hello";//(定义并)初始化变量v
因为javascript为动态语言，其变量并没有固定的类型，其存储空间大小会随初始化与赋值而变化，所以其变量的“定义”就不像传统的静态语言一样了，其定义显得无关紧要。


####简单介绍下函数声明，变量声明

```js

//函数声明：
function test(){},

//变量声明：
var xxx;
var xxx = 1;
var xxx = function xxx(){}

这几种都算是变量声明

```
 

这里需要说明的是，虽然所有的申明（包括ES5的var、function，和ES6的function *、let、const、class）都会被提升，但是var、function、function *和let、const、class的的提升却并不相同！具体原因可以看这里的说明（大体的意思是虽然let，const，class也被提升了，但是却并不会被初始化，这时候去访问他们则会报ReferenceError异常，他们需要到语句执行的时候才会被初始化，而在被初始化之前的状态叫做temporal dead zone）。

比如看看这个例子：

```js

function f(){
    console.log(a);
    var a = "hello";
}

f();

    输出： undefined
    这里a被提升，但因为定义在后，所以输出undefined
```
 
####重复声明

```js
var x = 1;
console.log(x);
if(true){
    var x = 2;
    console.llog(x);
}
console.log(x);  

这里的var x = 2的申明会被忽略，仅用于赋值。因为实际上x只会在最顶上开始的地方申明一次


等价于

var x = 1;
console.log(x);
if(true){
    x = 2;
    console.llog(x);
}
console.log(x);  
```

####声明提升
当前作用域内的声明都会提升到作用域的最前面，包括变量和函数的声明

```js
(function(){
 var a = "1";
 var f = function(){};
 var b = "2";
 var c = "3";
})(); 

```
变量a,f,b,c的声明会被提升到函数作用域的最前面，类似如下：

```js
(function(){
 var a,f,b,c;
 a = "1";
 f = function(){};
 b = "2";
 c = "3";
})();
```

####总结
要彻底理解JS的作用域和Hoisting，只要记住以下三点即可：

      1、所有申明都会被提升到作用域的最顶上

      2、同一个变量申明只进行一次，并且因此其他申明都会被忽略

      3、函数声明的优先级优于变量申明，且函数声明会连带定义一起被提升




 