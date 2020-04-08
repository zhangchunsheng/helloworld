package com.luomor.config;

import com.mongodb.MongoClient;
import com.mongodb.MongoClientURI;
import com.luomor.config.props.MultipleMongoProperties;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.autoconfigure.mongo.MongoProperties;
import org.springframework.boot.context.properties.EnableConfigurationProperties;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.context.annotation.Primary;
import org.springframework.data.mongodb.MongoDbFactory;
import org.springframework.data.mongodb.core.MongoTemplate;
import org.springframework.data.mongodb.core.SimpleMongoDbFactory;
import org.springframework.data.mongodb.repository.config.EnableMongoRepositories;


@Configuration
@EnableConfigurationProperties(MultipleMongoProperties.class)
@EnableMongoRepositories(basePackages = "com.luomor.repository.primary",
		mongoTemplateRef = "primaryMongoTemplate")
public class PrimaryMongoConfig {
}
