//
//  SQLiteDatabase.h
//  HelloWorld
//
//  Created by Peter Zhang on 14-2-19.
//  Copyright (c) 2014年 Peter Zhang. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <sqlite3.h>

@interface SQLiteDatabase : NSObject {
    sqlite3 *database;
}

- (id)initWithPath:(NSString *) path;
- (NSArray *)performQuery:(NSString *) query;

@end
