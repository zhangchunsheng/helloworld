//
//  HelloWorldViewController.h
//  HelloWorld
//
//  Created by Peter Zhang on 14-2-19.
//  Copyright (c) 2014年 Peter Zhang. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "HelloWorldAppDelegate.h"

@interface HelloWorldViewController : UIViewController

@property(nonatomic, retain) IBOutlet UILabel *nameLabel;
@property(nonatomic, retain) IBOutlet UILabel *sexLabel;

@property(nonatomic, retain) IBOutlet UITextField *textName;
@property(nonatomic, retain) IBOutlet UITextField *textSex;
@property(nonatomic, retain) IBOutlet UITextField *textAge;

@property(nonatomic, retain) IBOutlet UITextField *titleTextField;
@property(nonatomic, retain) IBOutlet UITextField *contentTextField;
@property(nonatomic, strong) HelloWorldAppDelegate *myDelegate;
@property(nonatomic, strong) NSMutableArray *entries;

@property(copy, nonatomic) NSString *databaseFilePath;

- (IBAction)showMessage;

//这个方法定义的是当应用程序退到后台时将执行的方法，按下home键执行（通知中心来调度）
- (void)applicationWillResignActive:(NSNotification *) notification;

//当通过键盘在UITextField中输入完毕后，点击屏幕空白区域关闭键盘的操作
- (IBAction)backgroundTapped:(id)sender;

//单击按钮后执行数据保存操作
- (IBAction)addToDB:(id)sender;

//单击按钮后执行查询操作
- (IBAction)queryFromDB:(id)sender;

@end
