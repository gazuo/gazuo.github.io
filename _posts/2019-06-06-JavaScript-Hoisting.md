# JS中作用域和变量提升(hoisting)的深入理

写Js也写了挺久了。但是很多基础知识还是挺差的。

废话不多说直接来看一段代码

```js
var str = "hello world";
function f() {
    console.log(str);
    var str = "hello";
}
f();
```

很多人会说是hello world,但是结果是 undefined,为什么会这样？这种就引出一个概念--hoisting

var hoisting
    Because variable declarations (and declarations in general) are processed before any code is executed, declaring a variable anywhere in the code is equivalent to declaring it at the top. This also means that a variable can appear to be used before it's declared. This behavior is called "hoisting", as it appears that the variable declaration is moved to the top of the function or global code.

这段话翻译下来就是

因为变量申明是在任意代码执行前处理的，在代码区中任意地方申明变量和在最开始（最上面）的地方申明是一样的。也就是说，看起来一个变量可以在申明之前被使用！这种行为就是所谓的“hoisting”，也就是变量提升，看起来就像变量的申明被自动移动到了函数或全局代码的最顶上。

**注意：**仅仅是申明提升了，定义并不会被提升。

 