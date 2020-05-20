---
title: Laya接入原生Banner
subtitle: 'Laya接入原生Banner'
author: 王壮壮
layout: post
date: 2020-05-20
categories: [Laya]
tags: [Laya]
---
 
### 由于工作需要Laya接入原生SDK,接入激励视频和图文信息流功能。遇到banner需要在增加一个布局文件才能显示的问题。因为对android原生层面不是很熟悉。故记录一下解决过程。
 
# 解决过程

### 技术分析：查看Laya接入工程发现只存在一个android布局界面显示Laya渲染的游戏界面百度搜索一番尝试写了一个布局文件折腾都失败发现setContentView后会覆盖掉游戏界面。或者添加SDK banner View的是空异常报错。

### 下面直接贴代码


1. 修改布局文件


```  
		activity_main_game.xml  布局文件

		<?xml version="1.0" encoding="utf-8"?>
		<FrameLayout xmlns:android="http://schemas.android.com/apk/res/android"
			xmlns:tools="http://schemas.android.com/tools"
			tools:ignore="HardcodedText"
			android:layout_width="match_parent"
			android:layout_height="match_parent">

			<FrameLayout
				android:id="@+id/game_container"
				android:layout_width="match_parent"
				android:layout_height="match_parent">
			</FrameLayout>

			<FrameLayout
				android:id="@+id/banner_container"
				android:layout_width="wrap_content"
				android:layout_height="wrap_content"
				android:layout_gravity="bottom"
				android:background="#D5D5D5">
			</FrameLayout>

		</FrameLayout>
```


2. 主文件修改android界面布局文件。分成游戏容器和banner容器

```
	//找到MainActivity.java
	public void initEngine()
	{
		mProxy = new RuntimeProxy(this);
		mPlugin = new GameEngine(this);
		mPlugin.game_plugin_set_runtime_proxy(mProxy);
		mPlugin.game_plugin_set_option("localize","true");
		mPlugin.game_plugin_set_option("gameUrl", "http://stand.alone.version/index.html");
		mPlugin.game_plugin_init(3);
		View gameView = mPlugin.game_plugin_get_view();
		//修改布局文件 分成banner和游戏布局
		this.setContentView(R.layout.activity_main_game);
		gameContainer = findViewById(R.id.game_container);
		gameContainer.addView(gameView);
		isLoad=true;

		//在需要展示banner的地方 保留当前界面句柄 setView(bannerView) 就好了
		//bannerContainer  = _activity.findViewById(R.id.banner_container);

		//初始化SDK 
	}

```
 
*** 
### 总结：主要就是重写Laya的布局文件，原本的布局只有一个只有   
```
	View gameView = mPlugin.game_plugin_get_view();
	this.setContentView(gameView);

``` 



