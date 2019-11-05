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
##         打包Python程序成exe

python 环境python 2.7x
 
0：安装python环境包和pywin32；
1：下载pyinstaller，解压缩（201403，最新版本为2.1）
2：setup.py install 进行安装；
3：pyinstaller.py -F xxx.py；
4：会生成xxx目录，进入dist目录找到生成的exe文件。
 
快捷复制下面代码 新建一个bat 
'''python
set/p option= input file Name:
python E:\Tools\pyinstaller-2.0\pyinstaller.py -F -c %option%
pause
'''