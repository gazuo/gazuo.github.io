---
title:  Python 打包成exe程序
subtitle: 'exe不用关心Python环境问题'
author: 王壮壮
layout: post
date: 2019-11-5
categories: [Python]
tags: [Python]
typora-root-url: ..\assets\img
---
 
#### 安装环境
python 环境python 2.7x  (建议最低版本2.7.14以后)
[Python2.7.14](https://www.python.org/downloads/release/python-2714/)
缺失的库采用 pip install 安装

#### 安装步骤

1. 安装python环境包和pywin32；
2. 下载pyinstaller，解压缩（201403，最新版本为2.1）
3. setup.py install 进行安装；
4. pyinstaller.py -F xxx.py；
5. 会生成xxx目录，进入dist目录找到生成的exe文件。
 

#### 打包exe

快捷复制下面代码 新建一个bat
```python 
  set/p option= input file Name:
  python E:\Tools\pyinstaller-2.0\pyinstaller.py -F -c %option%
  pause
  
  如果集成到python环境中直接
  
  pyinstaller  -F -c %option%
  pause

```

#### 可能遇到的问题
1. 如果你安装的版本 执行打包exe出现  ImportError: cannot import name ‘_imaging 这类问题
解决方案：安装高版本的pyinstaller
列如2.7.14 需要下载对应的 [PyInstaller-2.1.zip](https://p.shipengliang.com/f/11753764-293917002-82cb40)
