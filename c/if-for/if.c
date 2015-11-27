#include <stdio.h>

int main() {
    int age;
    printf("请输入你的年龄：");
    scanf("%d", &age);
    if(age >= 18) {
        printf("恭喜，你已经成年，可以使用该软件！\n");
    } else {
        printf("抱歉，你还未成年，不宜使用该软件！\n");
    }
}
