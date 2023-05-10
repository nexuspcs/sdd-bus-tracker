//
//  GNJavascriptRunner.h
//  Pods
//
//  Created by Hunaid Hassan on 07.07.21.
//

#import <Foundation/Foundation.h>

@protocol GNJavascriptRunner;

@protocol GNJavascriptRunner<NSObject>

- (void)runJavascript:(NSString *)script;
- (void)loadUrl:(NSURL *)url;

@end
