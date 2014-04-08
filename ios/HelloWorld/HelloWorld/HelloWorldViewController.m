//
//  HelloWorldViewController.m
//  HelloWorld
//
//  Created by Peter Zhang on 14-2-19.
//  Copyright (c) 2014年 Peter Zhang. All rights reserved.
//

#import "HelloWorldViewController.h"
#import "sqlite3.h"
#import "Entity.h"

//数据库文件的名字
#define databaseName @"database.sqlite3"

@interface HelloWorldViewController()

@end

@implementation HelloWorldViewController

@synthesize nameLabel;
@synthesize sexLabel;

@synthesize textName;
@synthesize textSex;
@synthesize textAge;

@synthesize titleTextField;
@synthesize contentTextField;
@synthesize myDelegate = _myDelegate;
@synthesize entries = _entries;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
}

- (void)viewDidLoad
{
    [super viewDidLoad];
    // Do any additional setup after loading the view from its nib.
    NSUserDefaults *myDefaults = [NSUserDefaults standardUserDefaults];
    [myDefaults setObject:@"hello" forKey:@"defaultKey"];
    
    NSLog(@"The value is %@", [myDefaults objectForKey:@"defaultKey"]);
    
    [[NSUserDefaults standardUserDefaults] setObject:@"Peter" forKey:@"name"];
    self.nameLabel.text = [[NSUserDefaults standardUserDefaults] stringForKey:@"name"];
    
    [[NSUserDefaults standardUserDefaults] setObject:@1 forKey:@"sex"];
    int sex = [[NSUserDefaults standardUserDefaults] integerForKey:@"sex"];
    self.sexLabel.text = [NSString stringWithFormat:@"%i", sex];
    
    
    //sqlite
    NSArray *path = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES);
    NSString *documentDirectory = [path objectAtIndex:0];
    
    //指定数据库文件的路径
    self.databaseFilePath = [documentDirectory stringByAppendingPathComponent:databaseName];
    
    //打开数据库
    sqlite3 *database;
    //[self.databaseFilePath UTF8String];将OC字符串转换成C字符串
    if(sqlite3_open([self.databaseFilePath UTF8String], &database) != SQLITE_OK) {
        //关闭数据库
        sqlite3_close(database);
        NSAssert(0, @"数据库打开失败");
    }
    
    //创建表格
    NSString *createSql = @"create table if not exists student (id integer primary key,field_data text);";
    //若发生错误，则错误信息存在该字符串中
    char *errorMsg;
    
    //sqlite3_exec这个方法可以执行那些没有返回结果的操作，例如创建、插入、删除等，这个函数包含了sqlite3_prepare这个函数的操作，目的是将UTF-8格式的SQL语句转换为编译后的语句
    if(sqlite3_exec(database, [createSql UTF8String], NULL, NULL, &errorMsg)) {
        sqlite3_close(database);
        NSAssert(0, @"创建表错误：%s", errorMsg);
    }
    
    //查询数据库
    NSString *querySql = @"select * from student order by id";
    //语句句柄
    sqlite3_stmt *statement;
    //sqlite3_prepare_v2的作用是将UTF-8格式的SQL语句转换为编译后的语句，并返回指向该语句的指针
    if(sqlite3_prepare_v2(database, [querySql UTF8String], -1, &statement, nil) == SQLITE_OK) {
        //sqlite3_step的作用是移动一行记录，SQLITE_ROW代表一行
        while(sqlite3_step(statement) == SQLITE_ROW) {
            //sqlite3_column_int返回当前行的一个int类型的值，sqlite3_column_text返回一个字符串类型的值，后面的数据对应每一列
            int id = sqlite3_column_int(statement, 0);
            char *rowData = (char *)sqlite3_column_text(statement, 1);
            //如果要得到一个NSString字符串，可以采用如下方法
            //NSString *str = [NSString stringWithUTF8String:(char *)sqlite3_column_text(statement, 2)];
            
            //通过id得到UI控件，类似Android中的findViewById(id)
            UITextField *textField = (UITextField *)[self.view viewWithTag:id];
            
            //[[NSString alloc]initWithUTF8String:rowData] 将char转换成OC字符串
            textField.text = [[NSString alloc]initWithUTF8String:rowData];
            
            NSLog(@"The id is %i", id);
            NSLog(@"The text is %s", rowData);
        }
        //删除编译后的语句
        sqlite3_finalize(statement);
    }
    sqlite3_close(database);
    
    //注册通知，当程序将要退出到后台时执行applicationWillResignActive方法（在.h文件中定义）
    UIApplication *application = [UIApplication sharedApplication];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(applicationWillResignActive:) name:UIApplicationWillResignActiveNotification object:application];
    
    //获取当前应用程序的委托(UIApplication sharedApplication为整个应用程序上下文)
    self.myDelegate = (HelloWorldAppDelegate *)[[UIApplication sharedApplication] delegate];
}

- (void)viewDidUnload
{
    [self setTitleTextField:nil];
    [self setContentTextField:nil];
    
    [super viewDidUnload];
    // Release any retained subviews of the main view.
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation
{
    return (interfaceOrientation != UIInterfaceOrientationPortraitUpsideDown);
}

//按下Home键程序将要进入后台前保存UITextField中的数据
- (void)applicationWillResignActive:(NSNotification *)notification
{
    NSLog(@"applicationWillResignActive");
    sqlite3 *database;
    if(sqlite3_open([self.databaseFilePath UTF8String], &database) != SQLITE_OK) {
        NSAssert(0, @"打开数据库错误");
        sqlite3_close(database);
    }
    
    for(int i = 1 ; i <= 3 ; i++) {
        UITextField *textField = (UITextField *)[self.view viewWithTag:i];
        
        //插入数据
        char *update = "insert or replace into student values (?,?)";
        sqlite3_stmt *statement;
        if(sqlite3_prepare_v2(database, update, -1, &statement, nil) == SQLITE_OK) {
            //将值保存到指定的列
            sqlite3_bind_int(statement, 1, i);
            
            //第四个参数代表第三个参数中需要传递的长度。对于C字符串来说，-1表示传递全部字符串。第五个参数是一个回调函数，比如执行后做内存清除工作。
            sqlite3_bind_text(statement, 2, [textField.text UTF8String], -1, NULL);
        }
        
        char *errorMsg = NULL;
        //SQLITE_DONE表示更新数据库是否完成
        if(sqlite3_step(statement) != SQLITE_DONE) {
            NSAssert(0, @"更新数据出错： %s", errorMsg);
        }
        sqlite3_finalize(statement);
    }
    
    sqlite3_close(database);
}

- (IBAction)backgroundTapped:(id)sender
{
    /**
     * 当通过键盘在UITextField中输入完毕后，点击屏幕空白区域关闭键盘的操作
     */
    for(int i = 1 ; i <= 3 ; i++) {
        UITextField *textField = (UITextField *)[self.view viewWithTag:i];
        [textField resignFirstResponder];
    }
    
    [titleTextField resignFirstResponder];
    [contentTextField resignFirstResponder];
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (IBAction)showMessage
{
    UIAlertView *helloWorldAlert = [[UIAlertView alloc]initWithTitle:@"My First App" message:@"Hello World" delegate:nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
    
    //Display the Hello World Message
    [helloWorldAlert show];
}

- (void) dealloc {
    [titleTextField release];
    [contentTextField release];
    [super dealloc];
}

//单击按钮后执行数据库保存操作
- (IBAction)addToDB:(id)sender {
    //让CoreData在上下文中创建一个新对象（托管对象）
    Entity *entity = (Entity *)[NSEntityDescription insertNewObjectForEntityForName:@"Entity" inManagedObjectContext:self.myDelegate.managedObjectContext];
    
    [entity setTitle:self.titleTextField.text];
    [entity setBody:self.contentTextField.text];
    [entity setCreateDate:[NSString stringWithFormat:@"%@",[NSDate date]]];
    
    NSError *error;
    //托管对象准备好后，调用托管对象上下文的save方法将数据写入数据库
    BOOL isSaveSuccess = [self.myDelegate.managedObjectContext save:&error];
    
    if(!isSaveSuccess) {
        NSLog(@"Error: %@,%@", error, [error userInfo]);
    } else {
        NSLog(@"Save successful!");
    }
}

//单击按钮后执行查询操作
- (IBAction)queryFromDB:(id)sender {
    //创建取回数据请求
    NSFetchRequest *request = [[NSFetchRequest alloc] init];
    //设置要检索哪种类型的实体对象
    NSEntityDescription *entity = [NSEntityDescription entityForName:@"Entity" inManagedObjectContext:self.myDelegate.managedObjectContext];
    
    //设置请求实体
    [request setEntity:entity];
    //指定对结果的排序方式
    NSSortDescriptor *sortDescriptor = [[NSSortDescriptor alloc] initWithKey:@"createDate" ascending:NO];
    NSArray *sortDescriptions = [[NSArray alloc]initWithObjects:sortDescriptor, nil];
    [request setSortDescriptors:sortDescriptions];
    [sortDescriptions release];
    [sortDescriptor release];
    
    NSError *error = nil;
    //执行获取数据请求，返回数据
    NSMutableArray *mutableFetchResult = [[self.myDelegate.managedObjectContext executeFetchRequest:request error:&error] mutableCopy];
    if(mutableFetchResult == nil) {
        NSLog(@"Error: %@,%@", error, [error userInfo]);
    }
    self.entries = mutableFetchResult;
    
    NSLog(@"The count of entity:%i",[self.entries count]);
    
    for(Entity *entity in self.entries) {
        NSLog(@"Title:%@---Content:%@---Date:%@", entity.title, entity.body, entity.createDate);
    }
    
    [mutableFetchResult release];
    [request release];
}

//删除操作
- (void)deleteEntity:(Entity *)entity {
    [self.myDelegate.managedObjectContext deleteObject:entity];
    [self.entries removeObject:entity];
    
    NSError *error;
    if(![self.myDelegate.managedObjectContext save:&error]) {
        NSLog(@"Error:%@,%@", error, [error userInfo]);
    }
}

@end
