---
title:Creator接入原生Banner
subtitle: 'Creator接入原生Banner'
author: 王壮壮
layout: post
date: 2020-05-23
categories: [Creator]
tags: [Creator]
---
 
### 由于工作需要Laya接入原生SDK,接入激励视频和图文信息流功能。遇到banner需要在增加一个布局文件才能显示的问题。因为对android原生层面不是很熟悉。故记录一下解决过程。
 
# 解决过程

### 技术分析：查看Laya接入工程发现只存在一个android布局界面显示Laya渲染的游戏界面百度搜索一番尝试写了一个布局文件折腾都失败发现setContentView后会覆盖掉游戏界面。或者添加SDK banner View的是空异常报错。

### 下面直接贴代码


1. 修改布局文件


```xml 
		activity_native_express.xml  布局文件

		<?xml version="1.0" encoding="utf-8"?>
		<RelativeLayout xmlns:android="http://schemas.android.com/apk/res/android"
			xmlns:tools="http://schemas.android.com/tools"
			tools:ignore="HardcodedText"
			android:layout_width="match_parent"
			android:layout_height="match_parent">
			<RelativeLayout
				android:id="@+id/express_container"
				android:layout_width="wrap_content"
				android:layout_height="340dp"
				android:background="#D5D5D5"
				android:layout_alignParentBottom="true">
			</RelativeLayout>
		</RelativeLayout>

```


2. 主文件修改android界面布局文件。分成游戏容器和banner容器

```Java
	//找到AppActivity.java
	 onCreate方法里面：
	 //增加代码
	View layout = LayoutInflater.from(this).inflate(R.layout.activity_native_express,null);
	mFrameLayout.addView(layout);
	getGLSurfaceView().getHolder().setFormat(PixelFormat.TRANSLUCENT);


	//在你需要setView广告的地方增加
	 RelativeLayout mExpressContainer = (RelativeLayout) activity.findViewById(R.id.express_container);
	 mExpressContainer.setView(广告View);

```
 
*** 
### 总结：主要就是重写Laya的布局文件，原本的布局只有一个只有   

```Java
	一定要主要这块 要先添加view后执行后面的  不然会在被游戏层挡住
	getGLSurfaceView().getHolder().setFormat(PixelFormat.TRANSLUCENT);
``` 



