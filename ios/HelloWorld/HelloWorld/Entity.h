//
//  Entity.h
//  HelloWorld
//
//  Created by Peter Zhang on 14-2-19.
//  Copyright (c) 2014年 Peter Zhang. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>


@interface Entity : NSManagedObject

@property (nonatomic, retain) NSString * title;
@property (nonatomic, retain) NSString * createDate;
@property (nonatomic, retain) NSString * body;

@end
