//
//  HelloWorldAppDelegate.h
//  HelloWorld
//
//  Created by Peter Zhang on 14-2-19.
//  Copyright (c) 2014年 Peter Zhang. All rights reserved.
//

#import <UIKit/UIKit.h>
//引入CoreData框架
#import <CoreData/CoreData.h>

@class HelloWorldViewController;

@interface HelloWorldAppDelegate : UIResponder <UIApplicationDelegate>

@property (strong, nonatomic) UIWindow *window;
@property (strong, nonatomic) HelloWorldViewController *viewController;

//Core Data
//数据模型对象
@property (strong, nonatomic) NSManagedObjectModel *managedObjectModel;
//上下文对象
@property (strong, nonatomic) NSManagedObjectContext * managedObjectContext;
//持久性存储区
@property (strong, nonatomic) NSPersistentStoreCoordinator *persistentStoreCoordinator;

//初始化Core Data使用的数据库
- (NSPersistentStoreCoordinator *)persistentStoreCoordinator;

//managedObjectModel的初始化赋值函数
- (NSManagedObjectModel *)managedObjectModel;

//managedObjectContent的初始化赋值函数
- (NSManagedObjectContext *)managedObjectContext;

@end
