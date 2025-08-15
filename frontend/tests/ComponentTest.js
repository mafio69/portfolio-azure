// Basic frontend component test using Node.js
// Tests Vue component files for proper structure and exports

const fs = require('fs');
const path = require('path');

class ComponentTest {
    constructor() {
        this.testsRun = 0;
        this.testsPassed = 0;
    }

    runAllTests() {
        console.log('Running frontend component tests...\n');
        
        this.testProjectsGridComponent();
        this.testAppComponent();
        this.testMainTsStructure();
        
        console.log(`\nTest Results: ${this.testsPassed}/${this.testsRun} passed`);
        
        if (this.testsPassed === this.testsRun) {
            console.log('✅ All frontend tests passed!');
        } else {
            console.log('❌ Some frontend tests failed!');
            process.exit(1);
        }
    }

    testProjectsGridComponent() {
        this.testsRun++;
        const componentPath = path.join(__dirname, '../src/components/ProjectsGrid.vue');
        
        try {
            if (fs.existsSync(componentPath)) {
                const content = fs.readFileSync(componentPath, 'utf8');
                
                const checks = [
                    { name: 'has template section', test: content.includes('<template>') },
                    { name: 'has script section', test: content.includes('<script') },
                    { name: 'has Vue composition', test: content.includes('defineComponent') || content.includes('setup') || content.includes('<script setup') }
                ];
                
                const passed = checks.every(check => check.test);
                
                if (passed) {
                    this.testsPassed++;
                    console.log('✅ testProjectsGridComponent: PASSED');
                } else {
                    console.log('❌ testProjectsGridComponent: FAILED');
                    checks.filter(check => !check.test).forEach(check => {
                        console.log(`   - Missing: ${check.name}`);
                    });
                }
            } else {
                console.log('❌ testProjectsGridComponent: FAILED - File not found');
            }
        } catch (error) {
            console.log(`❌ testProjectsGridComponent: FAILED - ${error.message}`);
        }
    }

    testAppComponent() {
        this.testsRun++;
        const componentPath = path.join(__dirname, '../src/App.vue');
        
        try {
            if (fs.existsSync(componentPath)) {
                const content = fs.readFileSync(componentPath, 'utf8');
                
                const checks = [
                    { name: 'has template section', test: content.includes('<template>') },
                    { name: 'has v-app wrapper', test: content.includes('<v-app>') },
                    { name: 'has script section', test: content.includes('<script') },
                    { name: 'has Vue composition', test: content.includes('export default') || content.includes('<script setup') }
                ];
                
                const passed = checks.every(check => check.test);
                
                if (passed) {
                    this.testsPassed++;
                    console.log('✅ testAppComponent: PASSED');
                } else {
                    console.log('❌ testAppComponent: FAILED');
                    checks.filter(check => !check.test).forEach(check => {
                        console.log(`   - Missing: ${check.name}`);
                    });
                }
            } else {
                console.log('❌ testAppComponent: FAILED - File not found');
            }
        } catch (error) {
            console.log(`❌ testAppComponent: FAILED - ${error.message}`);
        }
    }

    testMainTsStructure() {
        this.testsRun++;
        const mainPath = path.join(__dirname, '../src/main.ts');
        
        try {
            if (fs.existsSync(mainPath)) {
                const content = fs.readFileSync(mainPath, 'utf8');
                
                const checks = [
                    { name: 'imports createApp', test: content.includes('createApp') },
                    { name: 'imports App', test: content.includes('App') },
                    { name: 'calls registerPlugins', test: content.includes('registerPlugins') },
                    { name: 'mounts app', test: content.includes('.mount(') }
                ];
                
                const passed = checks.every(check => check.test);
                
                if (passed) {
                    this.testsPassed++;
                    console.log('✅ testMainTsStructure: PASSED');
                } else {
                    console.log('❌ testMainTsStructure: FAILED');
                    checks.filter(check => !check.test).forEach(check => {
                        console.log(`   - Missing: ${check.name}`);
                    });
                }
            } else {
                console.log('❌ testMainTsStructure: FAILED - File not found');
            }
        } catch (error) {
            console.log(`❌ testMainTsStructure: FAILED - ${error.message}`);
        }
    }
}

// Run the tests
const test = new ComponentTest();
test.runAllTests();