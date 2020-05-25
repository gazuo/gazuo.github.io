---
title: Creator and Banner
subtitle: 'Creator接入原生Banner'
author: 王壮壮
layout: post
date: 2020-05-23
categories: [Creator]
tags: [Creator]
---
 
### 由于工作需要Creator接入原生SDK,接入激励视频和图文信息流功能。遇到banner需要在增加一个布局文件才能显示的问题。因为对android原生层面不是很熟悉。故记录一下解决过程。
 
# 解决过程

### 技术分析：查看穿山甲接入工程发现只存在一个android布局界面显示Creator渲染的游戏界面百度搜索一番尝试写了一个布局文件折腾都失败发现setContentView后会覆盖掉游戏界面。或者添加SDK banner View的是空异常报错。

### 下面直接贴代码


1. 修改布局文件


```xml 
//activity_native_express.xml  布局文件   布局是靠下居中布局

<?xml version="1.0" encoding="utf-8"?>
<RelativeLayout xmlns:android="http://schemas.android.com/apk/res/android"
    xmlns:tools="http://schemas.android.com/tools"
    tools:ignore="HardcodedText"
    android:layout_width="match_parent"
    android:layout_height="match_parent">

    <ScrollView
        android:layout_width="match_parent"
        android:layout_height="wrap_content"
        android:layout_alignParentBottom="true">
        <FrameLayout
            android:id="@+id/express_container"
            android:layout_width="wrap_content"
            android:layout_height="wrap_content"
            android:layout_gravity="center_horizontal"
            android:background="#D5D5D5" />
    </ScrollView>
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
### 总结： 主要就是在游戏层上加一个布局文件，展示banner或者图文信息流。图文信息流有个小坑就是 。  一般我们都是放到弹窗上展示，但是如果直接关闭。sdk层也在渲染就会展示不对，如果直接在sdk层remove了。下次打开很大可能渲染不出来。但是游戏逻辑弹窗一般都会很快被关闭。如果保证banner一定渲染完成才能关闭，很影响体验。所以在android层做个状态判断。

```Java

//例如穿山甲的图文信息流
package com.xx.xx.ttapi; 
import android.content.Context;
import android.util.Log;
import android.view.View;
import android.widget.FrameLayout;
import android.widget.Toast;

import com.bytedance.sdk.openadsdk.AdSlot;
import com.bytedance.sdk.openadsdk.FilterWord;
import com.bytedance.sdk.openadsdk.TTAdConstant;
import com.bytedance.sdk.openadsdk.TTAdDislike;
import com.bytedance.sdk.openadsdk.TTAdNative;
import com.bytedance.sdk.openadsdk.TTAppDownloadListener;
import com.bytedance.sdk.openadsdk.TTNativeExpressAd;
import com.xx.xx.R;
import com.xx.xx.dialog.DislikeDialog;
import org.cocos2dx.javascript.AppActivity;
import org.cocos2dx.javascript.Constants;
import org.cocos2dx.javascript.uikit.TToast;
import org.cocos2dx.lib.Cocos2dxJavascriptJavaBridge;
import java.util.List;

public class TTGameExpressAd {
    public static final String TAG = "TTGameExpressAd";
    public static AppActivity activity ;
    private static TTAdNative mTTAdNative;
    private static FrameLayout mExpressContainer;
    private static TTNativeExpressAd mTTAd;
    private static long startTime = 0;
    private static boolean mHasShowDownloadActive = false;
    private static boolean isLoadedState = false; //加载广告状态
    private static boolean isAdShow = false;  //配合游戏逻辑直接被关闭后的逻辑
    //视频广告发送奖励
    private static void sendReward(final int code){
        TTGameExpressAd.activity.runOnGLThread(new Runnable() {
            @Override
            public void run() {
                //自己游戏奖励
                Cocos2dxJavascriptJavaBridge.evalString(String.format("SDKMgr.androidVideoFinish(%s)",String.valueOf( code )));
            }
        });
    }

    public static void init(Context content){
        activity = (AppActivity)content;
        //step2:创建TTAdNative对象，createAdNative(Context context) banner广告context需要传入Activity对象
        mTTAdNative = TTAdManagerHolder.get().createAdNative(content);
        //step3:(可选，强烈建议在合适的时机调用):申请部分权限，如read_phone_state,防止获取不了imei时候，下载类广告没有填充的问题。
        TTAdManagerHolder.get().requestPermissionIfNecessary(content);

        //重点******  这是自定义的布局用来展示banner或者图文信息流的
        mExpressContainer = (FrameLayout) activity.findViewById(R.id.express_container);
    }

    public static void viewShow(){
        activity.runOnUiThread(new Runnable() {
            @Override
            public void run() {
                //显示
                mExpressContainer.setVisibility(View.VISIBLE);
            }
        });
    }


    public static void loadExpressAd() {
        //step4:创建广告请求参数AdSlot,具体参数含义参考文档
        isLoadedState = false;
        isAdShow = true; 
        float expressViewWidth = 350;
        float expressViewHeight = 200;
        AdSlot adSlot = new AdSlot.Builder()
                .setCodeId(Constants.TT_FEED_ID) //广告位id
                .setSupportDeepLink(true)
                .setAdCount(1) //请求广告数量为1到3条
                .setExpressViewAcceptedSize(expressViewWidth,expressViewHeight) //期望模板广告view的size,单位dp
                .build();

        //step5:请求广告，对请求回调的广告作渲染处理
        mTTAdNative.loadNativeExpressAd(adSlot, new TTAdNative.NativeExpressAdListener() {
            @Override
            public void onError(int code, String message) { 
                //mExpressContainer.removeAllViews();
            }

            @Override
            public void onNativeExpressAdLoad(List<TTNativeExpressAd> ads) {
                if (ads == null || ads.size() == 0){
                    return;
                }
                System.out.println("loadExpressAd");
                mTTAd = ads.get(0);
                bindAdListener(mTTAd);
                mTTAd.render();
            }
        });
    }

    public static void bindAdListener(TTNativeExpressAd ad) {
        ad.setExpressInteractionListener(new TTNativeExpressAd.ExpressAdInteractionListener() {
            @Override
            public void onAdClicked(View view, int type) {
                //TToast.show(mContext, "广告被点击");
            }

            @Override
            public void onAdShow(View view, int type) {
              TToast.show(activity, "广告展示");
            }

            @Override
            public void onRenderFail(View view, String msg, int code) {
                Log.e("ExpressView","render fail:"+(System.currentTimeMillis() - startTime));
                TToast.show(activity, msg+" code:"+code);
            }

            @Override
            public void onRenderSuccess(final View view, float width, float height) {
                //返回view的宽高 单位 dp
                mExpressContainer.removeAllViews();
                activity.runOnUiThread(new Runnable() {
                    @Override
                    public void run() {
                        if(isAdShow){
                            mExpressContainer.addView(view);
                            viewShow();
                        }
                        isLoadedState = true;

                    }
                });
            }
        });
        //dislike设置
        bindDislike(ad, false);
        if (ad.getInteractionType() != TTAdConstant.INTERACTION_TYPE_DOWNLOAD){
            return;
        }
        ad.setDownloadListener(new TTAppDownloadListener() {
            @Override
            public void onIdle() {
                TToast.show(activity, "点击开始下载", Toast.LENGTH_LONG);
            }

            @Override
            public void onDownloadActive(long totalBytes, long currBytes, String fileName, String appName) {
                if (!mHasShowDownloadActive) {
                    mHasShowDownloadActive = true;
                    TToast.show(activity, "下载中，点击暂停", Toast.LENGTH_LONG);
                }
            }

            @Override
            public void onDownloadPaused(long totalBytes, long currBytes, String fileName, String appName) {
                TToast.show(activity, "下载暂停，点击继续", Toast.LENGTH_LONG);
            }

            @Override
            public void onDownloadFailed(long totalBytes, long currBytes, String fileName, String appName) {
                TToast.show(activity, "下载失败，点击重新下载", Toast.LENGTH_LONG);
            }

            @Override
            public void onInstalled(String fileName, String appName) {
                TToast.show(activity, "安装完成，点击图片打开", Toast.LENGTH_LONG);
            }

            @Override
            public void onDownloadFinished(long totalBytes, String fileName, String appName) {
                TToast.show(activity, "点击安装", Toast.LENGTH_LONG);
            }
        });
    }

    /**
     * 设置广告的不喜欢，注意：强烈建议设置该逻辑，如果不设置dislike处理逻辑，则模板广告中的 dislike区域不响应dislike事件。
     * @param ad
     * @param customStyle 是否自定义样式，true:样式自定义
     */
    public static void bindDislike(TTNativeExpressAd ad, boolean customStyle) {
        if (customStyle) {
            //使用自定义样式
            List<FilterWord> words = ad.getFilterWords();
            if (words == null || words.isEmpty()) {
                return;
            }

            final DislikeDialog dislikeDialog = new DislikeDialog(activity, words);
            dislikeDialog.setOnDislikeItemClick(new DislikeDialog.OnDislikeItemClick() {
                @Override
                public void onItemClick(FilterWord filterWord) {
                    //用户选择不喜欢原因后，移除广告展示 
                    mExpressContainer.removeAllViews();
                }
            });
            ad.setDislikeDialog(dislikeDialog);
            return;
        }
        //使用默认模板中默认dislike弹出样式
        ad.setDislikeCallback(activity, new TTAdDislike.DislikeInteractionCallback() {
            @Override
            public void onSelected(int position, String value) {
               // TToast.show(mContext, "点击 " + value);
                //用户选择不喜欢原因后，移除广告展示 
                mExpressContainer.removeAllViews();
            }

            @Override
            public void onCancel() {
                //TToast.show(activity, "点击取消 ");
            }
        });
    }

    //装载成功
    public static boolean getAdLoaded(){
        return  isLoadedState ;
    }

    //游戏逻辑调用关闭的  只是隐藏
    public static void onDestroyExpress() {
        activity.runOnUiThread(new Runnable() {
            @Override
            public void run() {
                isAdShow = false;
                mExpressContainer.setVisibility(View.GONE);
            }
        });
    }

    public static void onDestroy() {
        if (mTTAd != null) {
            mTTAd.destroy();
        }
    }

}
 
	 
``` 



